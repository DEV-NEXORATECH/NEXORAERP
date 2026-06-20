<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\ClientContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientContractController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $contracts = ClientContract::latest()->paginate(20);
        return view('erp.client-contracts.index', compact('contracts'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        return view('erp.client-contracts.create', compact('clients'));
    }

    public function edit(ClientContract $contract): View
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        return view('erp.client-contracts.edit', compact('contract', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id'      => ['nullable', 'exists:clients,id'],
            'title'          => ['required', 'max:255'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reminder_date'  => ['nullable', 'date'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'status'         => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
            'notes'          => ['nullable'],
        ]);

        $row = ClientContract::create($data + ['contract_number' => $this->nextNumber('CTR-NX', ClientContract::count() + 1)]);
        $this->audit('created', $row, 'Client contract dibuat');

        return redirect()->route('client-contracts.index')->with('status', 'Contract lifecycle berhasil dicatat.');
    }

    public function update(Request $request, ClientContract $contract): RedirectResponse
    {
        $old = $contract->toArray();
        $contract->update($request->validate([
            'client_id'      => ['nullable', 'exists:clients,id'],
            'title'          => ['required', 'max:255'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reminder_date'  => ['nullable', 'date'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'status'         => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
            'notes'          => ['nullable'],
        ]));

        $this->audit('updated', $contract, 'Client contract diedit', $old, $contract->fresh()->toArray());

        return redirect()->route('client-contracts.index')->with('status', 'Contract berhasil diupdate.');
    }

    public function destroy(ClientContract $contract): RedirectResponse
    {
        $this->audit('deleted', $contract, 'Client contract dihapus', $contract->toArray());
        $contract->delete();

        return redirect()->route('client-contracts.index')->with('status', 'Contract berhasil dihapus.');
    }
}
