<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\ChartAccount;
use App\Models\JournalLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportTrialBalanceController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View|JsonResponse
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $type = $request->input('type');

        $query = JournalLine::query()
            ->join('journal_entries', 'journal_lines.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_accounts', 'journal_lines.chart_account_id', '=', 'chart_accounts.id')
            ->select(
                'chart_accounts.id',
                'chart_accounts.code',
                'chart_accounts.name',
                'chart_accounts.type',
                DB::raw('SUM(COALESCE(journal_lines.debit, 0)) as total_debit'),
                DB::raw('SUM(COALESCE(journal_lines.credit, 0)) as total_credit'),
            )
            ->when($dateFrom, fn ($q) => $q->whereDate('journal_entries.entry_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('journal_entries.entry_date', '<=', $dateTo))
            ->when($type, fn ($q) => $q->where('chart_accounts.type', $type))
            ->groupBy('chart_accounts.id', 'chart_accounts.code', 'chart_accounts.name', 'chart_accounts.type')
            ->orderBy('chart_accounts.code');

        $query = $this->applyCompanyContext($request, $query);

        $accounts = $query->get()->map(function ($row) {
            $normalDebit = in_array($row->type, ['asset', 'expense'], true);

            return [
                'id'       => $row->id,
                'code'     => $row->code,
                'name'     => $row->name,
                'type'     => $row->type,
                'debit'    => (float) $row->total_debit,
                'credit'   => (float) $row->total_credit,
                'balance'  => $normalDebit
                    ? (float) $row->total_debit - (float) $row->total_credit
                    : (float) $row->total_credit - (float) $row->total_debit,
            ];
        })->values();

        $types = ChartAccount::query()->select('type')->distinct()->orderBy('type')->pluck('type');

        if ($this->isApi()) {
            return $this->respond([
                'accounts' => $accounts,
                'totals'   => [
                    'debit'  => $accounts->sum('debit'),
                    'credit' => $accounts->sum('credit'),
                ],
            ]);
        }

        return view('erp.reports.trial-balance', compact('accounts', 'types'));
    }
}
