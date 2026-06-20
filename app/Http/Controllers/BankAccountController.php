<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $bankAccounts = $this->applyListFilters(BankAccount::orderBy('name'), $request, ['name', 'bank_name', 'account_number'])->paginate(15)->withQueryString();
        return view('erp.bank-accounts.index', compact('bankAccounts'));
    }

    public function create(): View
    {
        return view('erp.bank-accounts.create');
    }

    public function edit(BankAccount $bankAccount): View
    {
        return view('erp.bank-accounts.edit', compact('bankAccount'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'max:255'],
            'bank_name'       => ['nullable', 'max:100'],
            'account_number'  => ['nullable', 'max:100'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $bankAccount = BankAccount::create($data);
        $this->audit('created', $bankAccount, 'Bank account dibuat');

        return redirect()->route('bank-accounts.index')->with('status', 'Bank account berhasil ditambahkan.');
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'max:255'],
            'bank_name'       => ['nullable', 'max:100'],
            'account_number'  => ['nullable', 'max:100'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $old = $bankAccount->toArray();
        $bankAccount->update($data);
        $this->audit('updated', $bankAccount, 'Bank account diupdate', $old, $bankAccount->fresh()->toArray());

        return redirect()->route('bank-accounts.index')->with('status', 'Bank account berhasil diupdate.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        $this->audit('deleted', $bankAccount, 'Bank account dihapus', $bankAccount->toArray());
        $bankAccount->delete();

        return redirect()->route('bank-accounts.index')->with('status', 'Bank account berhasil dihapus.');
    }
}
