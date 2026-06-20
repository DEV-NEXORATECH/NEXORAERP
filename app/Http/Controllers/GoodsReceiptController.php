<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $receipts = GoodsReceipt::latest()->paginate(20);
        return view('erp.goods-receipts.index', compact('receipts'));
    }

    public function create(): View
    {
        $allOrders = PurchaseOrder::orderByDesc('id')->get(['id', 'number']);
        return view('erp.goods-receipts.create', compact('allOrders'));
    }

    public function edit(GoodsReceipt $receipt): View
    {
        $allOrders = PurchaseOrder::orderByDesc('id')->get(['id', 'number']);
        return view('erp.goods-receipts.edit', compact('receipt', 'allOrders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = GoodsReceipt::create($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'receipt_date'      => ['required', 'date'],
            'status'            => ['required', Rule::in(['received', 'partial', 'rejected'])],
            'notes'             => ['nullable'],
        ]));
        $this->audit('created', $row, 'Goods receipt dibuat');

        return redirect()->route('goods-receipts.index')->with('status', 'Goods receipt berhasil dicatat.');
    }

    public function update(Request $request, GoodsReceipt $receipt): RedirectResponse
    {
        $old = $receipt->toArray();
        $receipt->update($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'receipt_date'      => ['required', 'date'],
            'status'            => ['required', Rule::in(['received', 'partial', 'rejected'])],
            'notes'             => ['nullable'],
        ]));
        $this->audit('updated', $receipt, 'Goods receipt diedit', $old, $receipt->fresh()->toArray());

        return redirect()->route('goods-receipts.index')->with('status', 'Goods receipt berhasil diupdate.');
    }

    public function destroy(GoodsReceipt $receipt): RedirectResponse
    {
        $this->audit('deleted', $receipt, 'Goods receipt dihapus', $receipt->toArray());
        $receipt->delete();

        return redirect()->route('goods-receipts.index')->with('status', 'Goods receipt berhasil dihapus.');
    }
}
