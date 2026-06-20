<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\GoodsReceipt;
use App\Models\PurchaseMatch;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseMatchController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $matches = $this->applyListFilters(
            PurchaseMatch::with('purchaseOrder', 'goodsReceipt')->latest(),
            $request,
            ['reference', 'notes']
        )->paginate(20)->withQueryString();

        if ($request->filled('status') && in_array($request->input('status'), ['matched', 'unmatched', 'partial'])) {
            $matches->where('match_status', $request->input('status'));
        }

        return view('erp.purchase-matches.index', compact('matches'));
    }

    public function create(): View
    {
        $purchaseOrders = PurchaseOrder::orderByDesc('id')->get(['id', 'number']);
        $receipts = GoodsReceipt::orderByDesc('id')->get(['id']);
        return view('erp.purchase-matches.create', compact('purchaseOrders', 'receipts'));
    }

    public function edit(PurchaseMatch $match): View
    {
        $purchaseOrders = PurchaseOrder::orderByDesc('id')->get(['id', 'number']);
        $receipts = GoodsReceipt::orderByDesc('id')->get(['id']);
        return view('erp.purchase-matches.edit', compact('match', 'purchaseOrders', 'receipts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = PurchaseMatch::create($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'goods_receipt_id' => ['nullable', 'exists:goods_receipts,id'],
            'variance_amount' => ['nullable', 'numeric'],
            'match_status' => ['required', Rule::in(['matched', 'unmatched', 'partial'])],
            'matched_at' => ['nullable', 'date'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Purchase match dibuat');

        return redirect()->route('purchase-matches.index')->with('status', 'Purchase match berhasil ditambahkan.');
    }

    public function update(Request $request, PurchaseMatch $match): RedirectResponse
    {
        $old = $match->toArray();
        $match->update($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'goods_receipt_id' => ['nullable', 'exists:goods_receipts,id'],
            'variance_amount' => ['nullable', 'numeric'],
            'match_status' => ['required', Rule::in(['matched', 'unmatched', 'partial'])],
            'matched_at' => ['nullable', 'date'],
            'notes' => ['nullable'],
        ]));

        $this->audit('updated', $match, 'Purchase match diedit', $old, $match->fresh()->toArray());

        return redirect()->route('purchase-matches.index')->with('status', 'Purchase match berhasil diupdate.');
    }

    public function destroy(PurchaseMatch $match): RedirectResponse
    {
        $this->audit('deleted', $match, 'Purchase match dihapus', $match->toArray());
        $match->delete();

        return redirect()->route('purchase-matches.index')->with('status', 'Purchase match berhasil dihapus.');
    }
}
