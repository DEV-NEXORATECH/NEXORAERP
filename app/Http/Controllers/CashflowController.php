<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CashflowController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $cashflowPages   = Cashflow::with(['project:id,code'])->latest()->paginate(25);
        $allCashflows    = Cashflow::all();
        $summary         = $this->cashflowSummary($allCashflows);
        return view('erp.cashflows.index', compact('cashflowPages', 'summary'));
    }

    public function create(): View
    {
        $projects         = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->get();
        $bankAccounts     = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.cashflows.create', compact('projects', 'expenseCategories', 'bankAccounts'));
    }

    public function edit(Cashflow $cashflow): View
    {
        $projects         = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->get();
        $bankAccounts     = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.cashflows.edit', compact('cashflow', 'projects', 'expenseCategories', 'bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cashflow = Cashflow::create($request->validate($this->rules()));
        $this->audit('created', $cashflow, 'Cashflow dibuat');

        return back()->with('status', 'Cashflow berhasil dicatat.');
    }

    public function update(Request $request, Cashflow $cashflow): RedirectResponse
    {
        $old = $cashflow->toArray();
        $cashflow->update($request->validate($this->rules()));
        $this->audit('updated', $cashflow, 'Cashflow diedit', $old, $cashflow->fresh()->toArray());

        return back()->with('status', 'Cashflow berhasil diupdate.');
    }

    public function destroy(Cashflow $cashflow): RedirectResponse
    {
        $this->audit('deleted', $cashflow, 'Cashflow dihapus', $cashflow->toArray());
        $cashflow->delete();

        return back()->with('status', 'Cashflow berhasil dihapus.');
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
