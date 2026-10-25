@extends('layouts.erp', ['activePage' => 'sales-orders', 'pageTitle' => 'Sales Order '.$order->number])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('sales-orders.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Sales</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Sales Orders</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $order->number }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $order->number }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($order->status === 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-[#002875] border border-blue-200') }}">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('sales-orders.edit-page', $order) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Sales Order
            </a>
        </div>
    </div>

    {{-- Details & Items Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Order</h2>
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Klien / Customer</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $order->client->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Tanggal Order</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Proposal Referensi</dt>
                    <dd class="font-medium text-[#0F172A] mt-0.5">{{ $order->proposal->number ?? '-' }}</dd>
                </div>
                <div class="pt-2 border-t border-[#E5E7EB]">
                    <dt class="text-[#64748B]">Total Nilai Order</dt>
                    <dd class="text-xl font-bold text-[#15803D] mt-1">{{ $rp($order->amount) }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Rincian Item Produk (Items Ordered)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                            <th class="p-2.5 font-semibold text-[#475569]">Item / Produk</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-center">Qty</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-right">Harga Satuan</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($order->items as $item)
                            <tr>
                                <td class="p-2.5">
                                    <p class="font-bold text-[#0F172A]">{{ $item->product->name ?? $item->description ?? 'Item' }}</p>
                                    @if($item->product)<p class="text-[11px] text-[#64748B]">{{ $item->product->code }}</p>@endif
                                </td>
                                <td class="p-2.5 text-center font-bold text-[#0F172A]">{{ $item->quantity }}</td>
                                <td class="p-2.5 text-right font-medium text-[#475569]">{{ $rp($item->unit_price) }}</td>
                                <td class="p-2.5 text-right font-bold text-[#0F172A]">{{ $rp($item->quantity * $item->unit_price) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-[#94A3B8]">Belum ada item produk terdaftar pada order ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection