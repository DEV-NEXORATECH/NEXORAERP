<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportBankReconciliationController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $bankAccountId = $request->integer('bank_account_id') ?: null;

        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = BankAccount::orderBy('name')->get();

        $bankReconciliation = $bankAccounts->when($bankAccountId, fn ($c) => $c->where('id', $bankAccountId))
            ->map(function (BankAccount $bank) use ($dateFrom, $dateTo) {
                $flows = Cashflow::where('bank_account_id', $bank->id)
                    ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
                    ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
                    ->get();
                $summary = $this->cashflowSummary($flows);

                return [
                    'bank' => $bank,
                    'income' => $summary['income'],
                    'expense' => $summary['expense'],
                    'book_balance' => $bank->opening_balance + $summary['balance'],
                    'unreconciled' => 0,
                ];
            });

        return view('erp.reports.reconciliation', compact('bankReconciliation', 'projects', 'bankAccounts'));
    }
}
