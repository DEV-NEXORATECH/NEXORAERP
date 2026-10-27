<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\ChartAccount;
use App\Models\JournalLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ReportGeneralLedgerController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View|JsonResponse
    {
        $dateFrom = $request->date('date_from')?->startOfDay();
        $dateTo = $request->date('date_to')?->endOfDay();
        $chartAccountId = $request->integer('chart_account_id') ?: null;

        $query = JournalLine::query()
            ->with(['account', 'journalEntry.journal'])
            ->whereNotNull('chart_account_id')
            ->when($chartAccountId, fn ($q) => $q->where('journal_lines.chart_account_id', $chartAccountId))
            ->when($dateFrom, fn ($q) => $q->whereHas('journalEntry', fn ($je) => $je->whereDate('entry_date', '>=', $dateFrom)))
            ->when($dateTo, fn ($q) => $q->whereHas('journalEntry', fn ($je) => $je->whereDate('entry_date', '<=', $dateTo)))
            ->orderBy('chart_account_id')
            ->orderByRaw('(SELECT entry_date FROM journal_entries WHERE journal_entries.id = journal_lines.journal_entry_id)')
            ->orderBy('journal_lines.id');

        $query = $this->applyCompanyContext($request, $query);

        $lines = $query->get();

        $rows = [];
        $running = 0;
        $currentAccount = null;

        foreach ($lines as $line) {
            $account = $line->account;
            if (!$account) {
                continue;
            }

            if ($account->id !== $currentAccount) {
                $running = 0;
                $currentAccount = $account->id;
            }

            $running += (float) $line->debit - (float) $line->credit;

            $rows[] = [
                'account_id'    => $account->id,
                'account_code'  => $account->code,
                'account_name'  => $account->name,
                'date'          => $line->journalEntry?->entry_date,
                'number'        => $line->journalEntry?->number,
                'journal_code'  => $line->journalEntry?->journal?->code ?? $line->journalEntry?->number,
                'journal'       => $line->journalEntry?->journal?->name,
                'reference'     => $line->journalEntry?->reference,
                'description'   => $line->description ?? $line->journalEntry?->memo,
                'debit'         => (float) $line->debit,
                'credit'        => (float) $line->credit,
                'balance'       => round($running, 2),
            ];
        }

        $page = max(1, (int) $request->integer('page', 1));
        $perPage = 25;
        $paginated = new LengthAwarePaginator(
            collect($rows)->forPage($page, $perPage)->values(),
            count($rows),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $accounts = ChartAccount::query()->orderBy('code')->get(['id', 'code', 'name']);

        if ($this->isApi()) {
            return $this->respond($paginated);
        }

        return view('erp.reports.general-ledger', compact('paginated', 'accounts', 'chartAccountId'));
    }
}
