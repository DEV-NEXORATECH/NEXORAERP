<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $clients = $this->applyListFilters(Client::orderBy('name'), $request, ['name', 'contact_name', 'email'])->paginate(15)->withQueryString();
        return view('erp.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('erp.clients.create');
    }

    public function edit(Client $client): View
    {
        return view('erp.clients.edit', compact('client'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'max:255'],
            'contact_name' => ['nullable', 'max:255'],
            'email'        => ['nullable', 'email'],
            'phone'        => ['nullable', 'max:50'],
            'address'      => ['nullable'],
        ]);

        $client = Client::create($data);
        $this->audit('created', $client, 'Client dibuat');

        return redirect()->route('clients.index')->with('status', 'Client berhasil ditambahkan.');
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'max:255'],
            'contact_name' => ['nullable', 'max:255'],
            'email'        => ['nullable', 'email'],
            'phone'        => ['nullable', 'max:50'],
            'address'      => ['nullable'],
        ]);

        $old = $client->toArray();
        $client->update($data);
        $this->audit('updated', $client, 'Client diupdate', $old, $client->fresh()->toArray());

        return redirect()->route('clients.index')->with('status', 'Client berhasil diupdate.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->audit('deleted', $client, 'Client dihapus', $client->toArray());
        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Client berhasil dihapus.');
    }
}
