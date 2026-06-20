<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\RecurringBilling;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RecurringBillingController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $recurrings = $this->applyListFilters(
            RecurringBilling::with('client:id,name')->latest(),
            $request,
            ['title', 'description']
        )->paginate(20)->withQueryString();
        return view('erp.recurring-billings.index', compact('recurrings'));
    }

    public function create(): View
    {
        $clients = \App\Models\Client::orderBy('name')->get(['id', 'name']);
        return view('erp.recurring-billings.create', compact('clients'));
    }

    public function edit(RecurringBilling $recurringBilling): View
    {
        $clients = \App\Models\Client::orderBy('name')->get(['id', 'name']);
        return view('erp.recurring-billings.edit', compact('recurringBilling', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $billing = RecurringBilling::create($request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'name' => ['required', 'max:255'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'quarterly', 'yearly'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'next_invoice_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:next_invoice_date'],
            'status' => ['required', Rule::in(['active', 'paused', 'ended'])],
        ]));

        $this->audit('created', $billing, 'Recurring billing dibuat');

        return redirect()->route('recurring-billings.index')->with('status', 'Recurring billing berhasil ditambahkan.');
    }

    public function update(Request $request, RecurringBilling $recurringBilling): RedirectResponse
    {
        $old = $recurringBilling->toArray();
        $recurringBilling->update($request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'name' => ['required', 'max:255'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'quarterly', 'yearly'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'next_invoice_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:next_invoice_date'],
            'status' => ['required', Rule::in(['active', 'paused', 'ended'])],
        ]));

        $this->audit('updated', $recurringBilling, 'Recurring billing diedit', $old, $recurringBilling->fresh()->toArray());

        return redirect()->route('recurring-billings.index')->with('status', 'Recurring billing berhasil diupdate.');
    }

    public function destroy(RecurringBilling $recurringBilling): RedirectResponse
    {
        $this->audit('deleted', $recurringBilling, 'Recurring billing dihapus', $recurringBilling->toArray());
        $recurringBilling->delete();

        return redirect()->route('recurring-billings.index')->with('status', 'Recurring billing berhasil dihapus.');
    }
}
