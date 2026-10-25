@extends('layouts.erp', ['activePage' => 'purchase-orders', 'pageTitle' => 'Purchase Order'])

@section('content')
<div class="space-y-4">
    <!-- Card 1 — Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">Procurement</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-700">Purchase Orders</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Purchase Order (PO)</h1>
            <p class="mt-0.5 text-xs text-gray-500">Kelola master data, purchase order kepada vendor, dan pemantauan status penerimaan barang.</p>
        </div>
        <x-erp.toolbar-actions
            module="purchase-orders"
            :create-route="route('purchase-orders.create-page')"
            create-label="+ Tambah PO"
        />
    </div>

    <!-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) -->
    <div class="filter-card">
        <form method="get" action="{{ route('purchase-orders.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari PO..." value="{{ request('search') }}" class="filter-search !w-56 text-xs">
            @if(isset($vendors))
            <select name="vendor_id" class="text-xs">
                <option value="">Semua Vendor</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                @endforeach
            </select>
            @endif
            <select name="status" class="text-xs">
                <option value="">Semua Status</option>
                <option value="ordered" @selected(request('status') == 'ordered')>Ordered</option>
                <option value="partial_received" @selected(request('status') == 'partial_received')>Partial Received</option>
                <option value="received" @selected(request('status') == 'received')>Received</option>
                <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs" title="Dari Tanggal">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs" title="Sampai Tanggal">
            <button type="submit" class="inline-flex h-[34px] items-center px-3.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition flex-shrink-0 cursor-pointer">Filter</button>
            @if(request()->hasAny(['search', 'vendor_id', 'status', 'date_from', 'date_to']))
                <a href="{{ route('purchase-orders.index') }}" class="inline-flex h-[34px] items-center px-3 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition flex-shrink-0">Reset</a>
            @endif
        </form>
    </div>

    <!-- Card 3 — Table Card -->
    <div class="workspace-card bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">PO</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Vendor</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Amount</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Items</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($orders as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->number }}<br><span class="muted">{{ $row->order_date?->format('d M Y') }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->vendor->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($row->amount) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $row->items_count }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="badge badge-{{ $row->status === 'cancelled' ? 'void' : ($row->status === 'ordered' ? 'pending' : 'active') }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('purchase-orders.show', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="{{ route('purchase-orders.edit-page', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
                            <a href="{{ route('purchase-orders.pdf', $row) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-white text-[#475569] hover:bg-[#F3F4F6] hover:text-[#002875]" title="Cetak PDF">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            </a>
                            <form method="post" action="{{ route('purchase-orders.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus PO ini?');">
                                @csrf @method('delete')
                                <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada purchase order.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 4 — Pagination (Card Terpisah) -->
    <x-erp.pagination :paginator="$orders" />
</div>

<x-erp.import-modal module="purchase-orders" title="Impor Data Purchase Order" />
@endsection

