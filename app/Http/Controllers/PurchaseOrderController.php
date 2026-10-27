<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Http\Traits\SyncsOrderItems;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequisition;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    use LoadsErpData, AppliesListFilters, SyncsOrderItems;

    public function index(Request $request): View
    {
        $orders = $this->applyListFilters(
            PurchaseOrder::with('vendor')->withCount('items')->latest(),
            $request,
            ['number']
        )->paginate(8)->withQueryString();

        if ($request->filled('vendor_id')) {
            $orders->where('vendor_id', $request->input('vendor_id'));
        }

        if ($request->filled('date_from')) {
            $orders->whereDate('order_date', '>=', $request->date('date_from')->toDateString());
        }

        if ($request->filled('date_to')) {
            $orders->whereDate('order_date', '<=', $request->date('date_to')->toDateString());
        }

        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        return view('erp.purchase-orders.index', compact('orders', 'vendors'));
    }

    public function create(): View
    {
        $allVendors      = Vendor::orderBy('name')->get(['id', 'name']);
        $allRequisitions = PurchaseRequisition::orderByDesc('id')->get(['id', 'number', 'title']);
        $products        = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name', 'cost']);
        $productOptions  = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => (float) $product->cost,
        ])->values();

        return view('erp.purchase-orders.create', compact('allVendors', 'allRequisitions', 'products', 'productOptions'));
    }

        public function show(PurchaseOrder $order): View
    {
        $order->load(['vendor', 'requisition', 'items.product', 'goodsReceipts']);
        return view('erp.purchase-orders.show', compact('order'));
    }
public function edit(PurchaseOrder $order): View
    {
        $allVendors      = Vendor::orderBy('name')->get(['id', 'name']);
        $allRequisitions = PurchaseRequisition::orderByDesc('id')->get(['id', 'number', 'title']);
        $products        = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name', 'cost']);
        $productOptions  = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => (float) $product->cost,
        ])->values();

        return view('erp.purchase-orders.edit', compact('order', 'allVendors', 'allRequisitions', 'products', 'productOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $row = PurchaseOrder::create([
            'company_id' => auth()->user()?->company_id,
            'purchase_requisition_id' => $data['purchase_requisition_id'] ?? null,
            'vendor_id' => $data['vendor_id'] ?? null,
            'order_date' => $data['order_date'],
            'amount' => 0,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'number' => $this->nextNumber('PO-NX', PurchaseOrder::count() + 1),
        ]);

        $total = $this->syncOrderItems($row, PurchaseOrderItem::class, $request->input('items'));
        $row->update(['amount' => $total ?: $data['amount']]);

        if ($row->status === 'received') {
            $this->postOrderStockMovements($row, 'in', 'purchase_order', $row->number);
        }

        $this->audit('created', $row, 'Purchase order dibuat');

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil dibuat.');
    }

    public function update(Request $request, PurchaseOrder $order): RedirectResponse
    {
        $old = $order->fresh()->load('items')->toArray();
        $data = $this->validated($request);

        $order->update([
            'purchase_requisition_id' => $data['purchase_requisition_id'] ?? null,
            'vendor_id' => $data['vendor_id'] ?? null,
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        $total = $this->syncOrderItems($order, PurchaseOrderItem::class, $request->input('items'));
        $order->update(['amount' => $total ?: $data['amount']]);

        if ($order->status === 'received') {
            $this->postOrderStockMovements($order, 'in', 'purchase_order', $order->number);
        }

        $this->audit('updated', $order, 'Purchase order diedit', $old, $order->fresh()->load('items')->toArray());

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil diupdate.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'vendor_id'               => ['nullable', 'exists:vendors,id'],
            'order_date'              => ['required', 'date'],
            'amount'                  => ['nullable', 'numeric', 'min:0'],
            'status'                  => ['required', Rule::in(['ordered', 'partial_received', 'received', 'cancelled'])],
            'notes'                   => ['nullable'],
            'items'                   => ['nullable', 'array'],
            'items.*.product_id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'        => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price'      => ['nullable', 'numeric', 'min:0'],
            'items.*.notes'           => ['nullable', 'string'],
        ]);
    }

    public function pdf(PurchaseOrder $order): View
    {
        $order->load(['vendor', 'items.product', 'requisition']);
        $companySetting = \App\Models\CompanySetting::first();
        return view('erp.purchase-orders.print', [
            'order' => $order,
            'companySetting' => $companySetting,
        ]);
    }

    public function destroy(PurchaseOrder $order): RedirectResponse
    {
        $this->audit('deleted', $order, 'Purchase order dihapus', $order->toArray());
        $order->delete();

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil dihapus.');
    }
}

