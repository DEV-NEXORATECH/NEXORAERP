<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportTransactionsController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View|JsonResponse
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $projectId = $request->integer('project_id') ?: null;
        $bankAccountId = $request->integer('bank_account_id') ?: null;

        $transactions = $this->applyCompanyContext($request, Cashflow::with(['project:id,code,name', 'bankAccount:id,name']))
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($bankAccountId, fn ($q) => $q->where('bank_account_id', $bankAccountId))
            ->latest('transaction_date')
            ->paginate(25)
            ->withQueryString();

        $projects = $this->applyCompanyContext($request, Project::query())->orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = $this->applyCompanyContext($request, BankAccount::query())->orderBy('name')->get();

        if ($this->isApi()) {
            return $this->respond($transactions);
        }

        return view('erp.reports.transactions', compact('transactions', 'projects', 'bankAccounts'));
    }
}
