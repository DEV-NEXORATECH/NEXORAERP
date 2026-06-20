<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $orders = PurchaseOrder::latest()->paginate(20);
        return view('erp.purchase-orders.index', compact('orders'));
    }

    public function create(): View
    {
        $allVendors      = Vendor::orderBy('name')->get(['id', 'name']);
        $allRequisitions = PurchaseRequisition::orderByDesc('id')->get(['id', 'number', 'title']);
        return view('erp.purchase-orders.create', compact('allVendors', 'allRequisitions'));
    }

    public function edit(PurchaseOrder $order): View
    {
        $allVendors      = Vendor::orderBy('name')->get(['id', 'name']);
        $allRequisitions = PurchaseRequisition::orderByDesc('id')->get(['id', 'number', 'title']);
        return view('erp.purchase-orders.edit', compact('order', 'allVendors', 'allRequisitions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'vendor_id'               => ['nullable', 'exists:vendors,id'],
            'order_date'              => ['required', 'date'],
            'amount'                  => ['required', 'numeric', 'min:0'],
            'status'                  => ['required', Rule::in(['ordered', 'partial_received', 'received', 'cancelled'])],
            'notes'                   => ['nullable'],
        ]);

        $row = PurchaseOrder::create($data + ['number' => $this->nextNumber('PO-NX', PurchaseOrder::withTrashed()->count() + 1)]);
        $this->audit('created', $row, 'Purchase order dibuat');

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil dibuat.');
    }

    public function update(Request $request, PurchaseOrder $order): RedirectResponse
    {
        $old = $order->toArray();
        $order->update($request->validate([
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'vendor_id'               => ['nullable', 'exists:vendors,id'],
            'order_date'              => ['required', 'date'],
            'amount'                  => ['required', 'numeric', 'min:0'],
            'status'                  => ['required', Rule::in(['ordered', 'partial_received', 'received', 'cancelled'])],
            'notes'                   => ['nullable'],
        ]));
        $this->audit('updated', $order, 'Purchase order diedit', $old, $order->fresh()->toArray());

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil diupdate.');
    }

    public function destroy(PurchaseOrder $order): RedirectResponse
    {
        $this->audit('deleted', $order, 'Purchase order dihapus', $order->toArray());
        $order->delete();

        return redirect()->route('purchase-orders.index')->with('status', 'Purchase order berhasil dihapus.');
    }
}
