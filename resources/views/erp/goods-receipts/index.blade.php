@extends('layouts.erp', ['activePage' => 'goods-receipts', 'pageTitle' => 'Receipt Verification'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Penerimaan Barang (Goods Receipt)</h1>
            <p class="text-xs text-[#475569]">Kelola master data dan transaksi Penerimaan Barang (Goods Receipt)</p>
        </div>
        <x-erp.toolbar-actions module="goods-receipts" :create-route="route('goods-receipts.create-page')" create-label="+ Catat Penerimaan" />
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
        <form method="get" action="{{ route('goods-receipts.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            
        <select name="status" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="received" @selected(request('status') == 'received')>Received</option>
            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
            <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('goods-receipts.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">PO</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Catatan</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($receipts as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->purchaseOrder->number ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->receipt_date?->format('d M Y') }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $row->status === 'rejected' ? 'void' : 'active' }}">{{ $row->status }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ Str::limit($row->notes, 40) ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <a href="{{ route('goods-receipts.show', $row) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="{{ route('goods-receipts.edit-page', $row) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
                            <a href="{{ route('goods-receipts.pdf', $row) }}" target="_blank" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Cetak Surat Penerimaan PDF">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            </a>
                            <form method="post" action="{{ route('goods-receipts.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus receipt ini?">
                                @csrf @method('delete')
                                <button type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada receipt.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$receipts" />
</div>

<x-erp.import-modal module="goods-receipts" title="Impor Data Goods Receipts" />
@endsection