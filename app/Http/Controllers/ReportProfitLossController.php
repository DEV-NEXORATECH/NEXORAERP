<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportProfitLossController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $projectId = $request->integer('project_id') ?: null;
        $bankAccountId = $request->integer('bank_account_id') ?: null;

        $cashflows = $this->applyCompanyContext($request, Cashflow::with(['project:id,code,name', 'bankAccount:id,name']))
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($bankAccountId, fn ($q) => $q->where('bank_account_id', $bankAccountId))
            ->get();

        $summary = $this->cashflowSummary($cashflows);

        $profitLoss = [
            'revenue' => $summary['income'],
            'expense' => $summary['expense'],
            'net_profit' => $summary['balance'],
            'expense_by_category' => $cashflows->where('type', 'expense')->groupBy('category')->map->sum('amount'),
            'revenue_by_category' => $cashflows->where('type', 'income')->groupBy('category')->map->sum('amount'),
        ];

        $projects = $this->applyCompanyContext($request, Project::query())->orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = $this->applyCompanyContext($request, BankAccount::query())->orderBy('name')->get();

        return view('erp.reports.profit-loss', compact('profitLoss', 'projects', 'bankAccounts'));
    }
}
