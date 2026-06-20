<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\Budget;
use App\Models\Cashflow;
use App\Models\ChartAccount;
use App\Models\Invoice;
use App\Models\JournalLine;
use App\Models\Project;
use App\Models\TaxRule;
use App\Models\VendorBill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $reportType = $request->string('report', 'profit_loss')->toString();
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $projectId = $request->integer('project_id') ?: null;
        $bankAccountId = $request->integer('bank_account_id') ?: null;

        $cashflowQuery = Cashflow::with(['project:id,code,name', 'bankAccount:id,name'])
            ->when($dateFrom, fn ($query) => $query->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('transaction_date', '<=', $dateTo))
            ->when($projectId, fn ($query) => $query->where('project_id', $projectId))
            ->when($bankAccountId, fn ($query) => $query->where('bank_account_id', $bankAccountId));

        $cashflows = (clone $cashflowQuery)->get();
        $summary = $this->cashflowSummary($cashflows);
        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        $bankAccounts = BankAccount::orderBy('name')->get();

        $projectReports = Project::with(['cashflows', 'salaries', 'reimbursements', 'invoices'])
            ->when($projectId, fn ($query) => $query->whereKey($projectId))
            ->get()
            ->map(fn ($p) => [
            'project'             => $p,
            'summary'             => $this->cashflowSummary($p->cashflows),
            'profit_margin'       => $p->contract_value > 0 ? ($this->cashflowSummary($p->cashflows)['balance'] / $p->contract_value * 100) : 0,
            'salary_total'        => $p->salaries->where('status', 'paid')->sum('net_salary'),
            'reimbursement_total' => $p->reimbursements->where('status', 'paid')->sum('amount'),
            'invoice_total'       => $p->invoices->sum('amount'),
        ]);

        $profitLoss = [
            'revenue' => $summary['income'],
            'expense' => $summary['expense'],
            'net_profit' => $summary['balance'],
            'expense_by_category' => $cashflows->where('type', 'expense')->groupBy('category')->map->sum('amount'),
            'revenue_by_category' => $cashflows->where('type', 'income')->groupBy('category')->map->sum('amount'),
        ];

        $balanceSheet = $this->balanceSheet();
        $cashFlowStatement = [
            'operating_in' => $cashflows->where('type', 'income')->sum('amount'),
            'operating_out' => $cashflows->where('type', 'expense')->sum('amount'),
            'net_cash' => $summary['balance'],
            'by_month' => $cashflows->groupBy(fn ($flow) => Carbon::parse($flow->transaction_date)->format('Y-m'))->map(fn ($rows) => $this->cashflowSummary($rows)),
        ];
        $agingAr = $this->aging(Invoice::whereNotIn('status', ['paid', 'void'])->get(), 'due_date');
        $agingAp = $this->aging(VendorBill::whereIn('status', ['unpaid', 'partial'])->get(), 'due_date');
        $taxSummary = $this->taxSummary($dateFrom, $dateTo);
        $budgetVsActual = Budget::with(['project:id,code,name', 'account:id,code,name'])->latest()->get()->map(function (Budget $budget) {
            $actual = Cashflow::query()
                ->when($budget->project_id, fn ($query) => $query->where('project_id', $budget->project_id))
                ->where('type', 'expense')
                ->where('transaction_date', 'like', $budget->period.'%')
                ->sum('amount');

            return [
                'budget' => $budget,
                'actual' => $actual,
                'variance' => $budget->budget_amount - $actual,
            ];
        });
        $transactions = (clone $cashflowQuery)->latest('transaction_date')->paginate(25)->withQueryString();
        $bankReconciliation = $bankAccounts->map(function (BankAccount $bank) use ($dateFrom, $dateTo) {
            $flows = Cashflow::where('bank_account_id', $bank->id)
                ->when($dateFrom, fn ($query) => $query->whereDate('transaction_date', '>=', $dateFrom))
                ->when($dateTo, fn ($query) => $query->whereDate('transaction_date', '<=', $dateTo))
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

        $reportOptions = [
            'profit_loss' => 'Laporan Laba Rugi',
            'balance_sheet' => 'Laporan Neraca',
            'cash_flow_statement' => 'Laporan Arus Kas',
            'project_profitability' => 'Laporan Profitabilitas Proyek',
            'aging_ar' => 'Laporan Umur Piutang',
            'aging_ap' => 'Laporan Umur Utang',
            'tax_summary' => 'Laporan Rekapitulasi Pajak',
            'budget_vs_actual' => 'Laporan Anggaran vs Realisasi',
            'transactions' => 'Transaction Listings',
            'bank_reconciliation' => 'Bank Reconciliation',
        ];

        return view('erp.reports.index', compact(
            'reportType',
            'reportOptions',
            'projects',
            'bankAccounts',
            'projectReports',
            'profitLoss',
            'balanceSheet',
            'cashFlowStatement',
            'agingAr',
            'agingAp',
            'taxSummary',
            'budgetVsActual',
            'transactions',
            'bankReconciliation'
        ));
    }

    private function balanceSheet(): array
    {
        $lines = JournalLine::with('account:id,code,name,type')->get();
        $groups = ['asset' => collect(), 'liability' => collect(), 'equity' => collect()];

        foreach ($lines->groupBy('chart_account_id') as $accountId => $rows) {
            $account = $rows->first()->account;
            if (! $account || ! isset($groups[$account->type])) {
                continue;
            }

            $balance = in_array($account->type, ['asset'], true)
                ? $rows->sum('debit') - $rows->sum('credit')
                : $rows->sum('credit') - $rows->sum('debit');
            $groups[$account->type]->push(['account' => $account, 'balance' => $balance]);
        }

        return [
            'assets' => $groups['asset'],
            'liabilities' => $groups['liability'],
            'equity' => $groups['equity'],
            'total_assets' => $groups['asset']->sum('balance'),
            'total_liabilities' => $groups['liability']->sum('balance'),
            'total_equity' => $groups['equity']->sum('balance'),
        ];
    }

    private function aging(Collection $rows, string $dateColumn): array
    {
        $buckets = ['current' => 0, '1_30' => 0, '31_60' => 0, '61_90' => 0, '90_plus' => 0];
        $items = $rows->map(function ($row) use (&$buckets, $dateColumn) {
            $outstanding = $row->amount - $row->paid_amount;
            $days = $row->{$dateColumn} ? Carbon::parse($row->{$dateColumn})->diffInDays(now(), false) : 0;
            $bucket = match (true) {
                $days <= 0 => 'current',
                $days <= 30 => '1_30',
                $days <= 60 => '31_60',
                $days <= 90 => '61_90',
                default => '90_plus',
            };
            $buckets[$bucket] += $outstanding;

            return compact('row', 'outstanding', 'days', 'bucket');
        });

        return ['buckets' => $buckets, 'items' => $items];
    }

    private function taxSummary(?Carbon $dateFrom, ?Carbon $dateTo): array
    {
        $invoiceTax = Invoice::query()
            ->when($dateFrom, fn ($query) => $query->whereDate('issue_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('issue_date', '<=', $dateTo))
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->amount * ($invoice->tax_rate / 100));
        $vendorTax = VendorBill::query()
            ->when($dateFrom, fn ($query) => $query->whereDate('bill_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('bill_date', '<=', $dateTo))
            ->get()
            ->sum(fn (VendorBill $bill) => $bill->amount * ($bill->tax_rate / 100));

        return [
            'invoice_tax' => $invoiceTax,
            'vendor_tax' => $vendorTax,
            'net_tax' => $invoiceTax - $vendorTax,
            'rules' => TaxRule::where('is_active', true)->orderBy('tax_type')->get(),
        ];
    }

    public function exportCashflows(): StreamedResponse
    {
        $rows = Cashflow::with('project')->orderBy('transaction_date')->get()->map(fn (Cashflow $flow) => [
            $flow->transaction_date,
            $flow->project?->code ?? 'Company',
            $flow->type,
            $flow->category,
            $flow->vendor,
            $flow->amount,
            $flow->description,
        ]);

        return $this->csv('cashflows.csv', ['Tanggal', 'Project', 'Type', 'Category', 'Vendor', 'Amount', 'Description'], $rows);
    }

    public function exportProjectFinance(): StreamedResponse
    {
        $rows = Project::with('cashflows')->get()->map(function (Project $project) {
            $summary = $this->cashflowSummary($project->cashflows);
            $margin  = $summary['income'] > 0 ? round(($summary['balance'] / $summary['income']) * 100, 2) : 0;

            return [$project->code, $project->name, $project->client, $project->contract_value, $summary['income'], $summary['expense'], $summary['balance'], $margin . '%'];
        });

        return $this->csv('project-finance.csv', ['Code', 'Project', 'Client', 'Contract', 'Income', 'Expense', 'Profit/Loss', 'Margin'], $rows);
    }
}
