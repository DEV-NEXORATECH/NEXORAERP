<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\ChartAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChartAccountController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $accounts = ChartAccount::with('parent:id,code,name')->orderBy('code')->paginate(20);
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.chart-accounts.index', compact('accounts', 'coaOptions'));
    }

    public function create(): View
    {
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.chart-accounts.create', compact('coaOptions'));
    }

    public function edit(ChartAccount $chartAccount): View
    {
        $coaOptions = ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        return view('erp.chart-accounts.edit', compact('chartAccount', 'coaOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $account = ChartAccount::create($request->validate([
            'code' => ['required', 'max:50', 'unique:chart_accounts,code'],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['asset', 'liability', 'equity', 'revenue', 'expense'])],
            'parent_id' => ['nullable', 'exists:chart_accounts,id'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('created', $account, 'Chart of account dibuat');

        return redirect()->route('chart-accounts.index')->with('status', 'CoA berhasil ditambahkan.');
    }

    public function update(Request $request, ChartAccount $chartAccount): RedirectResponse
    {
        $old = $chartAccount->toArray();
        $chartAccount->update($request->validate([
            'code' => ['required', 'max:50', Rule::unique('chart_accounts', 'code')->ignore($chartAccount)],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['asset', 'liability', 'equity', 'revenue', 'expense'])],
            'parent_id' => ['nullable', 'exists:chart_accounts,id'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        $this->audit('updated', $chartAccount, 'CoA diedit', $old, $chartAccount->fresh()->toArray());

        return redirect()->route('chart-accounts.index')->with('status', 'CoA berhasil diupdate.');
    }

    public function destroy(ChartAccount $chartAccount): RedirectResponse
    {
        $this->audit('deleted', $chartAccount, 'CoA dihapus', $chartAccount->toArray());
        $chartAccount->delete();

        return redirect()->route('chart-accounts.index')->with('status', 'CoA berhasil dihapus.');
    }
}
