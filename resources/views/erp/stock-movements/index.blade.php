@extends('layouts.erp', ['activePage' => 'stock-movements', 'pageTitle' => 'Stock Movements'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Perpindahan Stok (Movement)</h1>
            <p class="text-xs text-[#475569]">Kelola master data dan transaksi Perpindahan Stok (Movement)</p>
        </div>
        <x-erp.toolbar-actions module="stock-movements" :create-route="route('stock-movements.create-page')" create-label="+ Catat Perpindahan" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('stock-movements.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <select name="type">
            <option value="">Semua Tipe</option>
            @foreach(['in', 'out', 'adjustment'] as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('stock-movements.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Referensi</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Produk</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tipe</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Qty</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Gudang</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($movements as $movement)
            <tr>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $movement->reference ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $movement->movement_date }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $movement->product?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $movement->type === 'in' ? 'bg-emerald-100 text-emerald-700' : ($movement->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $movement->type === 'out' ? '-' : '' }}{{ rtrim(rtrim(number_format($movement->quantity, 4, '.', ''), '0'), '.') }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $movement->warehouse?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <form method="post" action="{{ route('stock-movements.destroy', $movement) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus pergerakan stok ini?">
                            @csrf
                            @method('delete')
                            <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-8 text-center text-slate-400">Belum ada movement stok.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$movements" />
</div>

<x-erp.import-modal module="stock-movements" title="Impor Data Stock Movements" />
@endsection