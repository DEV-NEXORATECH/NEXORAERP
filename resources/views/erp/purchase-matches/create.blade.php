@extends('layouts.erp', ['activePage' => 'purchase-matches.create-page', 'pageTitle' => 'Tambah Purchase Match'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Purchase Match</h2>
            <p class="muted">Catat pencocokan PO, receipt, dan bill.</p>
        </div>
        <form method="post" action="{{ route('purchase-matches.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Purchase Order</label>
                <select name="purchase_order_id">
                    <option value="">—</option>
                    @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}">{{ $po->number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Goods Receipt</label>
                <select name="goods_receipt_id">
                    <option value="">—</option>
                    @foreach($receipts as $receipt)
                        <option value="{{ $receipt->id }}">Receipt #{{ $receipt->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Variance Amount</label><input name="variance_amount" type="number" step="0.01" value="0"></div>
            <div class="grid gap-1.5"><label>Match Status</label>
                <select name="match_status">
                    <option value="matched">Matched</option>
                    <option value="unmatched">Unmatched</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Matched At</label><input name="matched_at" type="date" value="{{ now()->toDateString() }}"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Match
                </button>
                <a class="button ghost" href="{{ route('purchase-matches.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
