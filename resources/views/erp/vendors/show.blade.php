@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => $vendor->name.' Detail'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('vendors.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Procurement</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Vendors</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $vendor->name }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $vendor->name }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $vendor->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                        {{ $vendor->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('vendors.edit-page', $vendor) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Vendor
            </a>
        </div>
    </div>

    {{-- Details 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Rekanan</h2>
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Kategori Bisnis</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $vendor->category ?: 'General Supplier' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Kontak PIC</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $vendor->contact_name ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Email</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $vendor->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Telepon</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $vendor->phone ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Syarat Pembayaran</dt>
                    <dd class="font-medium text-[#0F172A] mt-0.5">{{ $vendor->payment_terms ?: 'Net 30 Days' }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Purchase Orders Terkait</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                            <th class="p-2.5 font-semibold text-[#475569]">Nomor PO</th>
                            <th class="p-2.5 font-semibold text-[#475569]">Tanggal Order</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-right">Nilai PO</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($vendor->purchaseOrders as $po)
                            <tr>
                                <td class="p-2.5 font-bold text-[#002875]">{{ $po->number }}</td>
                                <td class="p-2.5 text-[#475569]">{{ $po->order_date ? \Carbon\Carbon::parse($po->order_date)->format('d M Y') : '-' }}</td>
                                <td class="p-2.5 text-right font-bold text-[#15803D]">{{ $rp($po->total_amount) }}</td>
                                <td class="p-2.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-[#002875]">
                                        {{ $po->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-[#94A3B8]">Belum ada PO tercatat untuk vendor ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection