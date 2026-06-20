<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CashflowController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $filters = $request->only(['year', 'month', 'type', 'project_id', 'bank_account_id', 'cost_type', 'search']);

        $baseQuery = $this->buildQuery($filters);

        $cashflowPages = (clone $baseQuery)
            ->with(['project:id,code', 'expenseCategory:id,name', 'bankAccount:id,name'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString();

        $allFiltered    = (clone $baseQuery)->get(['type', 'amount', 'cost_type', 'project_id']);
        $summary        = $this->cashflowSummary($allFiltered);
        $summary['count'] = $allFiltered->count();

        $year            = (int) ($filters['year'] ?? now()->year);
        $monthlyBreakdown = $this->monthlyBreakdown($year);

        $byCostType = $allFiltered->groupBy('cost_type')
            ->map(fn ($g) => [
                'income'  => $g->where('type', 'income')->sum('amount'),
                'expense' => $g->where('type', 'expense')->sum('amount'),
                'count'   => $g->count(),
            ])
            ->sortByDesc(fn ($r) => $r['income'] + $r['expense']);

        $byProject = (clone $baseQuery)->with('project:id,code')->get(['type', 'amount', 'project_id'])
            ->groupBy(fn ($f) => $f->project?->code ?? 'Company')
            ->map(fn ($g) => [
                'income'  => $g->where('type', 'income')->sum('amount'),
                'expense' => $g->where('type', 'expense')->sum('amount'),
                'count'   => $g->count(),
            ])
            ->sortByDesc(fn ($r) => $r['income'] + $r['expense']);

        $projects     = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $bankAccounts = \App\Models\BankAccount::orderBy('name')->get(['id', 'name']);
        $costTypes    = ['operational', 'salary', 'reimbursement', 'tools', 'cloud', 'vendor', 'subcontractor', 'client_payment'];
        $years        = range(now()->year, now()->year - 4);

        return view('erp.cashflows.index', compact(
            'cashflowPages', 'summary', 'filters', 'year',
            'monthlyBreakdown', 'byCostType', 'byProject',
            'projects', 'bankAccounts', 'costTypes', 'years'
        ));
    }

    public function create(): View
    {
        $projects          = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->get();
        $bankAccounts      = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.cashflows.create', compact('projects', 'expenseCategories', 'bankAccounts'));
    }

    public function edit(Cashflow $cashflow): View
    {
        $projects          = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->get();
        $bankAccounts      = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.cashflows.edit', compact('cashflow', 'projects', 'expenseCategories', 'bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cashflow = Cashflow::create($request->validate($this->rules()));
        $this->audit('created', $cashflow, 'Cashflow dibuat');
        return redirect()->route('cashflows.index-page')->with('status', 'Cashflow berhasil dicatat.');
    }

    public function update(Request $request, Cashflow $cashflow): RedirectResponse
    {
        $old = $cashflow->toArray();
        $cashflow->update($request->validate($this->rules()));
        $this->audit('updated', $cashflow, 'Cashflow diedit', $old, $cashflow->fresh()->toArray());
        return redirect()->route('cashflows.index-page')->with('status', 'Cashflow berhasil diupdate.');
    }

    public function destroy(Cashflow $cashflow): RedirectResponse
    {
        $this->audit('deleted', $cashflow, 'Cashflow dihapus', $cashflow->toArray());
        $cashflow->delete();
        return back()->with('status', 'Cashflow berhasil dihapus.');
    }

    private function buildQuery(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $q = Cashflow::query();

        if (! empty($filters['month'])) {
            $y = str_pad((string) ($filters['year'] ?? now()->year), 4, '0', STR_PAD_LEFT);
            $m = str_pad((string) $filters['month'], 2, '0', STR_PAD_LEFT);
            $q->where('transaction_date', 'like', "$y-$m%");
        } elseif (! empty($filters['year'])) {
            $y = str_pad((string) $filters['year'], 4, '0', STR_PAD_LEFT);
            $q->where('transaction_date', 'like', "$y%");
        }

        if (! empty($filters['type'])) {
            $q->where('type', $filters['type']);
        }
        if (! empty($filters['project_id'])) {
            $q->where('project_id', $filters['project_id']);
        }
        if (! empty($filters['bank_account_id'])) {
            $q->where('bank_account_id', $filters['bank_account_id']);
        }
        if (! empty($filters['cost_type'])) {
            $q->where('cost_type', $filters['cost_type']);
        }
        if (! empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(fn ($sq) => $sq
                ->where('description', 'like', "%$s%")
                ->orWhere('category', 'like', "%$s%")
                ->orWhere('vendor', 'like', "%$s%")
            );
        }

        return $q;
    }

    private function monthlyBreakdown(int $year): array
    {
        $all = Cashflow::where('transaction_date', 'like', "$year%")
            ->get(['type', 'amount', 'transaction_date']);

        $rows = [];
        for ($m = 1; $m <= 12; $m++) {
            $month   = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $group   = $all->filter(fn ($f) => substr($f->transaction_date, 5, 2) === $month);
            $income  = $group->where('type', 'income')->sum('amount');
            $expense = $group->where('type', 'expense')->sum('amount');
            $rows[]  = [
                'month'   => $m,
                'label'   => Carbon::create(null, $m)->format('M'),
                'income'  => $income,
                'expense' => $expense,
                'net'     => $income - $expense,
            ];
        }

        return $rows;
    }

    private function rules(): array
    {
        return [
            'project_id'          => ['nullable', 'exists:projects,id'],
            'type'                => ['required', 'in:income,expense'],
            'category'            => ['required', 'max:100'],
            'expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            'bank_account_id'     => ['nullable', 'exists:bank_accounts,id'],
            'cost_type'           => ['required', Rule::in(['operational', 'salary', 'reimbursement', 'tools', 'cloud', 'vendor', 'subcontractor', 'client_payment'])],
            'vendor'              => ['nullable', 'max:255'],
            'description'         => ['required', 'max:255'],
            'amount'              => ['required', 'numeric', 'min:0'],
            'transaction_date'    => ['required', 'date'],
        ];
    }
}
