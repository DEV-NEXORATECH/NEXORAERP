<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\AccountingJournal;
use App\Models\ChartAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountingJournalController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $journals = $this->applyListFilters(AccountingJournal::orderBy('code'), $request, ['name', 'code'])->paginate(15)->withQueryString();
        return view('erp.accounting-journals.index', compact('journals'));
    }

    public function create(): View
    {
        $accounts = ChartAccount::orderBy('code')->get();
        return view('erp.accounting-journals.create', compact('accounts'));
    }

    public function edit(AccountingJournal $journal): View
    {
        $accounts = ChartAccount::orderBy('code')->get();
        return view('erp.accounting-journals.edit', compact('journal', 'accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $journal = AccountingJournal::create($data);
        $this->audit('created', $journal, 'Journal akuntansi dibuat');

        return redirect()->route('accounting-journals.index')->with('status', 'Journal berhasil ditambahkan.');
    }

    public function update(Request $request, AccountingJournal $journal): RedirectResponse
    {
        $data = $this->validated($request, $journal);
        $old = $journal->toArray();
        $journal->update($data);
        $this->audit('updated', $journal, 'Journal akuntansi diupdate', $old, $journal->fresh()->toArray());

        return redirect()->route('accounting-journals.index')->with('status', 'Journal berhasil diupdate.');
    }

    public function destroy(AccountingJournal $journal): RedirectResponse
    {
        $this->audit('deleted', $journal, 'Journal akuntansi dihapus', $journal->toArray());
        $journal->delete();

        return redirect()->route('accounting-journals.index')->with('status', 'Journal berhasil dihapus.');
    }

    private function validated(Request $request, ?AccountingJournal $journal = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('accounting_journals', 'code')->ignore($journal?->id)],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['sales', 'purchase', 'bank', 'cash', 'general', 'opening', 'adjustment', 'asset'])],
            'default_chart_account_id' => ['nullable', 'exists:chart_accounts,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
