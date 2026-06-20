@extends('layouts.erp', ['activePage' => 'purchase-order-create', 'pageTitle' => 'Buat Purchase Order'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Buat Purchase Order</h2>
            <p class="muted">Buat order ke vendor.</p>
        </div>
        <form method="post" action="{{ route('purchase-orders.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5 md:col-span-2"><label>PR Reference</label>
                <select name="purchase_requisition_id">
                    <option value="">Direct PO (tanpa PR)</option>
                    @foreach($allRequisitions as $pr)<option value="{{ $pr->id }}">{{ $pr->number }} - {{ $pr->title }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Vendor</label>
                <select name="vendor_id">
                    <option value="">Pilih vendor</option>
                    @foreach($allVendors as $vendor)<option value="{{ $vendor->id }}">{{ $vendor->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal Order</label><input name="order_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="ordered">Ordered</option>
                    <option value="partial_received">Partial Received</option>
                    <option value="received">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Catatan</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan PO
                </button>
                <a class="button ghost" href="{{ route('purchase-orders.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
