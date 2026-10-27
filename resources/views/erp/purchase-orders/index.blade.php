@extends('layouts.erp', ['activePage' => 'purchase-orders', 'pageTitle' => 'Purchase Order'])

@section('content')
<div class="space-y-4">
    <!-- Card 1 — Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">Procurement</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#334155]">Purchase Orders</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#0F172A] tracking-tight">Purchase Order (PO)</h1>
            <p class="mt-0.5 text-xs text-[#475569]">Kelola master data, purchase order kepada vendor, dan pemantauan status penerimaan barang.</p>
        </div>
        <x-erp.toolbar-actions
            module="purchase-orders"
            :create-route="route('purchase-orders.create-page')"
            create-label="+ Tambah PO"
        />
    </div>

    <!-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) -->
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
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
                <a href="{{ route('purchase-orders.index') }}" class="inline-flex h-[34px] items-center px-3 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition flex-shrink-0">Reset</a>
            @endif
        </form>
    </div>

    <!-- Card 3 — Table Card -->
    <div class="workspace-card bg-white rounded-lg border border-[#D1D5DB] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">PO</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Vendor</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Amount</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Items</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($orders as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->number }}<br><span class="muted">{{ $row->order_date?->format('d M Y') }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->vendor->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $rp($row->amount) }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->items_count }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $row->status === 'cancelled' ? 'void' : ($row->status === 'ordered' ? 'pending' : 'active') }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <x-erp.action-buttons
                            :show-url="route('purchase-orders.show', $row)"
                            :edit-url="route('purchase-orders.edit-page', $row)"
                            :print-url="route('purchase-orders.pdf', $row)"
                            :delete-url="route('purchase-orders.destroy', $row)"
                            :record-name="'PO ' . $row->number"
                        />
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

