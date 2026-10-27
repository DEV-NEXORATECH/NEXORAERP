@extends('layouts.erp', ['activePage' => 'fixed-assets.index', 'pageTitle' => 'Fixed Assets'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Aset Tetap (Fixed Assets)</h1>
            <p class="text-xs text-[#475569]">Kelola master data dan transaksi Aset Tetap (Fixed Assets)</p>
        </div>
        <x-erp.toolbar-actions module="fixed-assets" :create-route="route('fixed-assets.create-page')" create-label="+ Tambah Aset" />
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
        <form method="get" action="{{ route('fixed-assets.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <input type="text" name="category" placeholder="Kategori..." value="{{ request('category') }}" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <select name="status" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="disposed" @selected(request('status') == 'disposed')>Disposed</option>
            <option value="sold" @selected(request('status') == 'sold')>Sold</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('fixed-assets.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Kode</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Kategori</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Harga Perolehan</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Nilai Buku</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($assets as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->name }}<br><span class="muted">{{ $row->asset_code ?? '-' }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->category ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $rp($row->purchase_cost) }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $rp($row->book_value ?? $row->purchase_cost - $row->accumulated_depreciation) }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            @if($can('admin', 'finance'))
                                <a data-action="edit" href="{{ route('fixed-assets.edit-page', $row) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                            @endif
                            @if($can('admin', 'finance'))
                                <form method="post" action="{{ route('fixed-assets.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus aset tetap ini?">
                            @csrf
                            @method('delete')
                            <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada fixed asset.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$assets" />
</div>

<x-erp.import-modal module="fixed-assets" title="Impor Data Fixed Assets" />
@endsection