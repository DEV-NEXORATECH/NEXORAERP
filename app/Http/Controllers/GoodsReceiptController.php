<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $receipts = $this->applyListFilters(
            GoodsReceipt::with('purchaseOrder')->latest(),
            $request,
            ['notes']
        )->paginate(20)->withQueryString();

        if ($request->filled('date_from')) {
            $receipts->whereDate('receipt_date', '>=', $request->date('date_from')->toDateString());
        }

        if ($request->filled('date_to')) {
            $receipts->whereDate('receipt_date', '<=', $request->date('date_to')->toDateString());
        }

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
