<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $budgets = $this->applyListFilters(
            Budget::with(['project:id,code', 'account:id,code,name'])->latest(),
            $request,
            ['period', 'notes']
        )->paginate(20)->withQueryString();
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $coaOptions = \App\Models\ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.budgets.index', compact('budgets', 'projects', 'coaOptions'));
    }

    public function create(): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $coaOptions = \App\Models\ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.budgets.create', compact('projects', 'coaOptions'));
    }

    public function edit(Budget $budget): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $coaOptions = \App\Models\ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.budgets.edit', compact('budget', 'projects', 'coaOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $budget = Budget::create($request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'chart_account_id' => ['nullable', 'exists:chart_accounts,id'],
            'period' => ['required', 'max:20'],
            'budget_amount' => ['required', 'numeric', 'min:0'],
            'forecast_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $budget, 'Budget forecast dibuat');

        return redirect()->route('budgets.index')->with('status', 'Budget & forecast berhasil disimpan.');
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        $old = $budget->toArray();
        $budget->update($request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'chart_account_id' => ['nullable', 'exists:chart_accounts,id'],
            'period' => ['required', 'max:20'],
            'budget_amount' => ['required', 'numeric', 'min:0'],
            'forecast_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable'],
        ]));

        $this->audit('updated', $budget, 'Budget forecast diedit', $old, $budget->fresh()->toArray());

        return redirect()->route('budgets.index')->with('status', 'Budget & forecast berhasil diupdate.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $this->audit('deleted', $budget, 'Budget forecast dihapus', $budget->toArray());
        $budget->delete();

        return redirect()->route('budgets.index')->with('status', 'Budget & forecast berhasil dihapus.');
    }
}
