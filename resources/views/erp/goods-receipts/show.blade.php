@extends('layouts.erp', ['activePage' => 'goods-receipts', 'pageTitle' => 'Penerimaan Barang #'.$receipt->id])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('goods-receipts.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Procurement</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Goods Receipts</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">GR-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Goods Receipt #{{ $receipt->id }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $receipt->status === 'received' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ $receipt->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('goods-receipts.edit-page', $receipt) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Receipt
            </a>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs max-w-2xl">
        <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Penerimaan Fisik</h2>
        <dl class="space-y-3 text-xs">
            <div>
                <dt class="text-[#64748B]">Purchase Order Ref</dt>
                <dd class="font-bold text-[#0F172A] mt-0.5">{{ $receipt->purchaseOrder->number ?? 'Tanpa PO' }}</dd>
            </div>
            <div>
                <dt class="text-[#64748B]">Vendor / Supplier</dt>
                <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $receipt->purchaseOrder->vendor->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-[#64748B]">Tanggal Penerimaan</dt>
                <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') : '-' }}</dd>
            </div>
            <div class="pt-2 border-t border-[#E5E7EB]">
                <dt class="text-[#64748B]">Catatan Kondisi Barang</dt>
                <dd class="font-normal text-[#334155] mt-1">{{ $receipt->notes ?: 'Diterima dalam kondisi baik dan lengkap.' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection