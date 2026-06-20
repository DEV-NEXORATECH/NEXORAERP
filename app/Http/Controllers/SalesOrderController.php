<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\Proposal;
use App\Models\SalesOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesOrderController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = SalesOrder::latest()
            ->when($request->search, fn($q, $v) => $q->where('number', 'like', "%{$v}%"))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('order_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('order_date', '<=', $v))
            ->paginate(15)->withQueryString();
        return view('erp.sales-orders.index', compact('items'));
    }

    public function create(): View
    {
        $proposals = Proposal::orderByDesc('id')->get(['id', 'number', 'title']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        return view('erp.sales-orders.create', compact('proposals', 'clients'));
    }

    public function edit(SalesOrder $order): View
    {
        $proposals = Proposal::orderByDesc('id')->get(['id', 'number', 'title']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        return view('erp.sales-orders.edit', compact('order', 'proposals', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'client_id'   => ['nullable', 'exists:clients,id'],
            'title'       => ['required', 'max:255'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'order_date'  => ['required', 'date'],
            'status'      => ['required', Rule::in(['draft', 'confirmed', 'delivered', 'cancelled'])],
            'notes'       => ['nullable'],
        ]);

        $row = SalesOrder::create($data + ['number' => $this->nextNumber('SO-NX', SalesOrder::count() + 1)]);
        $this->audit('created', $row, 'Sales order dibuat');

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil dibuat.');
    }

    public function update(Request $request, SalesOrder $order): RedirectResponse
    {
        $old = $order->toArray();
        $order->update($request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'client_id'   => ['nullable', 'exists:clients,id'],
            'title'       => ['required', 'max:255'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'order_date'  => ['required', 'date'],
            'status'      => ['required', Rule::in(['draft', 'confirmed', 'delivered', 'cancelled'])],
            'notes'       => ['nullable'],
        ]));

        $this->audit('updated', $order, 'Sales order diedit', $old, $order->fresh()->toArray());

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil diupdate.');
    }

    public function destroy(SalesOrder $order): RedirectResponse
    {
        $this->audit('deleted', $order, 'Sales order dihapus', $order->toArray());
        $order->delete();

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil dihapus.');
    }
}
