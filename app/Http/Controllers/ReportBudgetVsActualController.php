<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Budget;
use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportBudgetVsActualController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $projects = $this->applyCompanyContext($request, Project::query())->orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = $this->applyCompanyContext($request, BankAccount::query())->orderBy('name')->get();

        $budgetVsActual = $this->applyCompanyContext($request, Budget::with(['project:id,code,name', 'account:id,code,name']))->latest()->get()->map(function (Budget $budget) use ($request) {
            $actual = $this->applyCompanyContext($request, Cashflow::query())
                ->when($budget->project_id, fn ($q) => $q->where('project_id', $budget->project_id))
                ->where('type', 'expense')
                ->where('transaction_date', 'like', $budget->period . '%')
                ->sum('amount');

            return [
                'budget' => $budget,
                'actual' => $actual,
                'variance' => $budget->budget_amount - $actual,
            ];
        });

        return view('erp.reports.budget', compact('budgetVsActual', 'projects', 'bankAccounts'));
    }
}
