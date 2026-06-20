<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportCashFlowController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $projectId = $request->integer('project_id') ?: null;
        $bankAccountId = $request->integer('bank_account_id') ?: null;

        $cashflows = Cashflow::with(['project:id,code,name', 'bankAccount:id,name'])
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($bankAccountId, fn ($q) => $q->where('bank_account_id', $bankAccountId))
            ->get();

        $summary = $this->cashflowSummary($cashflows);

        $cashFlowStatement = [
            'operating_in' => $cashflows->where('type', 'income')->sum('amount'),
            'operating_out' => $cashflows->where('type', 'expense')->sum('amount'),
            'net_cash' => $summary['balance'],
            'by_month' => $cashflows->groupBy(fn ($flow) => Carbon::parse($flow->transaction_date)->format('Y-m'))->map(fn ($rows) => $this->cashflowSummary($rows)),
        ];

        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = BankAccount::orderBy('name')->get();

        return view('erp.reports.cash-flow', compact('cashFlowStatement', 'projects', 'bankAccounts'));
    }
}
