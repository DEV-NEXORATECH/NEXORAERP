<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\ChartAccount;
use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $journals = JournalEntry::with('lines.account:id,code,name')->latest('entry_date')->paginate(20);
        return view('erp.journal-entries.index', compact('journals'));
    }

    public function create(): View
    {
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.journal-entries.create', compact('coaOptions'));
    }

    public function edit(JournalEntry $journalEntry): View
    {
        $journalEntry->load('lines');
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.journal-entries.edit', compact('journalEntry', 'coaOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_date' => ['required', 'date'],
            'reference' => ['nullable', 'max:100'],
            'memo' => ['nullable'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.chart_account_id' => ['required', 'exists:chart_accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'max:255'],
        ]);

        $debit = collect($data['lines'])->sum(fn ($line) => (float) ($line['debit'] ?? 0));
        $credit = collect($data['lines'])->sum(fn ($line) => (float) ($line['credit'] ?? 0));

        if ($debit <= 0 || round($debit, 2) !== round($credit, 2)) {
            return back()->withErrors(['journal' => 'Journal harus balance: total debit dan credit wajib sama.'])->withInput();
        }

        $journal = DB::transaction(function () use ($data) {
            $journal = JournalEntry::create([
                'number' => $this->nextNumber('JRN-NX', JournalEntry::count() + 1),
                'entry_date' => $data['entry_date'],
                'source' => 'manual',
                'reference' => $data['reference'] ?? null,
                'memo' => $data['memo'] ?? null,
            ]);

            foreach ($data['lines'] as $line) {
                $journal->lines()->create($line);
            }

            return $journal;
        });

        $this->audit('created', $journal, 'Journal entry dibuat');

        return redirect()->route('journal-entries.index')->with('status', 'Journal entry berhasil dibuat.');
    }

    public function update(Request $request, JournalEntry $journalEntry): RedirectResponse
    {
        $data = $request->validate([
            'entry_date' => ['required', 'date'],
            'reference' => ['nullable', 'max:100'],
            'memo' => ['nullable'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.chart_account_id' => ['required', 'exists:chart_accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'max:255'],
        ]);

        $debit = collect($data['lines'])->sum(fn ($line) => (float) ($line['debit'] ?? 0));
        $credit = collect($data['lines'])->sum(fn ($line) => (float) ($line['credit'] ?? 0));

        if ($debit <= 0 || round($debit, 2) !== round($credit, 2)) {
            return back()->withErrors(['journal' => 'Journal harus balance: total debit dan credit wajib sama.'])->withInput();
        }

        $old = $journalEntry->toArray();
        DB::transaction(function () use ($journalEntry, $data) {
            $journalEntry->update([
                'entry_date' => $data['entry_date'],
                'reference' => $data['reference'] ?? null,
                'memo' => $data['memo'] ?? null,
            ]);
            $journalEntry->lines()->delete();
            foreach ($data['lines'] as $line) {
                $journalEntry->lines()->create($line);
            }
        });

        $this->audit('updated', $journalEntry, 'Journal entry diedit', $old, $journalEntry->fresh()->toArray());

        return redirect()->route('journal-entries.index')->with('status', 'Journal entry berhasil diupdate.');
    }

    public function destroy(JournalEntry $journalEntry): RedirectResponse
    {
        $this->audit('deleted', $journalEntry, 'Journal entry dihapus', $journalEntry->toArray());
        $journalEntry->delete();

        return redirect()->route('journal-entries.index')->with('status', 'Journal entry berhasil dihapus.');
    }
}
