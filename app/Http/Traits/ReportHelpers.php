<?php

namespace App\Http\Traits;

use App\Models\Invoice;
use App\Models\JournalLine;
use App\Models\TaxRule;
use App\Models\VendorBill;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait ReportHelpers
{
    private function balanceSheet(): array
    {
        $lines = $this->applyCompanyContext(request(), JournalLine::with('account:id,code,name,type'))->get();
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
        $invoiceTax = $this->applyCompanyContext(request(), Invoice::query())
            ->when($dateFrom, fn ($query) => $query->whereDate('issue_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('issue_date', '<=', $dateTo))
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->amount * ($invoice->tax_rate / 100));
        $vendorTax = $this->applyCompanyContext(request(), VendorBill::query())
            ->when($dateFrom, fn ($query) => $query->whereDate('bill_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('bill_date', '<=', $dateTo))
            ->get()
            ->sum(fn (VendorBill $bill) => $bill->amount * ($bill->tax_rate / 100));

        return [
            'invoice_tax' => $invoiceTax,
            'vendor_tax' => $vendorTax,
            'net_tax' => $invoiceTax - $vendorTax,
            'rules' => $this->applyCompanyContext(request(), TaxRule::where('is_active', true))->orderBy('tax_type')->get(),
        ];
    }
}
