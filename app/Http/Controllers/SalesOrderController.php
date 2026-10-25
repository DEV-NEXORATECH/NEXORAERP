<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\SyncsOrderItems;
use App\Models\Client;
use App\Models\Product;
use App\Models\Proposal;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesOrderController extends Controller
{
    use LoadsErpData, SyncsOrderItems;

    public function index(Request $request): View
    {
        $items = SalesOrder::withCount('items')->latest()
            ->when($request->search, fn($q, $v) => $q->where('number', 'like', "%{$v}%"))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('order_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('order_date', '<=', $v))
            ->paginate(8)->withQueryString();
        return view('erp.sales-orders.index', compact('items'));
    }

    public function create(): View
    {
        $proposals = Proposal::orderByDesc('id')->get(['id', 'number', 'title']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $products = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name', 'price']);
        $productOptions = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => (float) $product->price,
        ])->values();

        return view('erp.sales-orders.create', compact('proposals', 'clients', 'products', 'productOptions'));
    }

        public function show(SalesOrder $order): View
    {
        $order->load(['proposal', 'client', 'items.product']);
        return view('erp.sales-orders.show', compact('order'));
    }
public function edit(SalesOrder $order): View
    {
        $proposals = Proposal::orderByDesc('id')->get(['id', 'number', 'title']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $products = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name', 'price']);
        $productOptions = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => (float) $product->price,
        ])->values();

        return view('erp.sales-orders.edit', compact('order', 'proposals', 'clients', 'products', 'productOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $row = SalesOrder::create([
            'company_id' => auth()->user()?->company_id,
            'proposal_id' => $data['proposal_id'] ?? null,
            'client_id' => $data['client_id'] ?? null,
            'title' => $data['title'],
            'amount' => 0,
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'number' => $this->nextNumber('SO-NX', SalesOrder::count() + 1),
        ]);

        $total = $this->syncOrderItems($row, SalesOrderItem::class, $request->input('items'));
        $row->update(['amount' => $total ?: $data['amount']]);

        if ($row->status === 'confirmed') {
            $this->postOrderStockMovements($row, 'out', 'sales_order', $row->number);
        }

        $this->audit('created', $row, 'Sales order dibuat');

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil dibuat.');
    }

    public function update(Request $request, SalesOrder $order): RedirectResponse
    {
        $old = $order->fresh()->load('items')->toArray();
        $data = $this->validated($request);

        $order->update([
            'proposal_id' => $data['proposal_id'] ?? null,
            'client_id' => $data['client_id'] ?? null,
            'title' => $data['title'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        $total = $this->syncOrderItems($order, SalesOrderItem::class, $request->input('items'));
        $order->update(['amount' => $total ?: $data['amount']]);

        if ($order->status === 'confirmed') {
            $this->postOrderStockMovements($order, 'out', 'sales_order', $order->number);
        }

        $this->audit('updated', $order, 'Sales order diedit', $old, $order->fresh()->load('items')->toArray());

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil diupdate.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'client_id'   => ['nullable', 'exists:clients,id'],
            'title'       => ['required', 'max:255'],
            'amount'      => ['nullable', 'numeric', 'min:0'],
            'order_date'  => ['required', 'date'],
            'status'      => ['required', Rule::in(['draft', 'confirmed', 'delivered', 'cancelled'])],
            'notes'       => ['nullable'],
            'items'       => ['nullable', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.notes'      => ['nullable', 'string'],
        ]);
    }

    public function destroy(SalesOrder $order): RedirectResponse
    {
        $this->audit('deleted', $order, 'Sales order dihapus', $order->toArray());
        $order->delete();

        return redirect()->route('sales-orders.index')->with('status', 'Sales order berhasil dihapus.');
    }
}

