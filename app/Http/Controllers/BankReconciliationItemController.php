<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\BankReconciliationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BankReconciliationItemController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $reconciliations = BankReconciliationItem::with('bankAccount')->latest()->paginate(20);
        return view('erp.bank-reconciliation-items.index', compact('reconciliations'));
    }

    public function create(): View
    {
        $banks = BankAccount::orderBy('name')->get(['id', 'name']);
        return view('erp.bank-reconciliation-items.create', compact('banks'));
    }

    public function edit(BankReconciliationItem $reconciliation): View
    {
        $banks = BankAccount::orderBy('name')->get(['id', 'name']);
        return view('erp.bank-reconciliation-items.edit', compact('reconciliation', 'banks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = BankReconciliationItem::create($request->validate([
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'statement_date' => ['required', 'date'],
            'description' => ['nullable', 'max:255'],
            'reference' => ['nullable', 'max:100'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'reconciled' => ['nullable', 'boolean'],
        ]));

        $this->audit('created', $row, 'Bank reconciliation item dibuat');

        return redirect()->route('bank-reconciliation-items.index')->with('status', 'Bank reconciliation item berhasil ditambahkan.');
    }

    public function update(Request $request, BankReconciliationItem $reconciliation): RedirectResponse
    {
        $old = $reconciliation->toArray();
        $reconciliation->update($request->validate([
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'statement_date' => ['required', 'date'],
            'description' => ['nullable', 'max:255'],
            'reference' => ['nullable', 'max:100'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['debit', 'credit'])],
            'reconciled' => ['nullable', 'boolean'],
        ]));

        $this->audit('updated', $reconciliation, 'Bank reconciliation item diedit', $old, $reconciliation->fresh()->toArray());

        return redirect()->route('bank-reconciliation-items.index')->with('status', 'Bank reconciliation item berhasil diupdate.');
    }

    public function destroy(BankReconciliationItem $reconciliation): RedirectResponse
    {
        $this->audit('deleted', $reconciliation, 'Bank reconciliation item dihapus', $reconciliation->toArray());
        $reconciliation->delete();

        return redirect()->route('bank-reconciliation-items.index')->with('status', 'Bank reconciliation item berhasil dihapus.');
    }
}
