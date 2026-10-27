@extends('layouts.erp', ['activePage' => 'bank-reconciliation-items.index', 'pageTitle' => 'Bank Reconciliation'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Rekonsiliasi Bank (Bank Reconciliation)</h1>
            <p class="text-xs text-[#475569]">Kelola master data dan transaksi Rekonsiliasi Bank (Bank Reconciliation)</p>
        </div>
        <x-erp.toolbar-actions module="bank-reconciliation-items" :create-route="route('bank-reconciliation-items.create-page')" create-label="+ Tambah Item Rekonsiliasi" />
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
        <form method="get" action="{{ route('bank-reconciliation-items.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            
        @if(isset($banks))
        <select name="bank_account_id" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Bank</option>
            @foreach($banks as $bank)
            <option value="{{ $bank->id }}" @selected(request('bank_account_id') == $bank->id)>{{ $bank->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="reconciled" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('reconciled') == '1')>Reconciled</option>
            <option value="0" @selected(request('reconciled') == '0')>Unreconciled</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('bank-reconciliation-items.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Bank</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Deskripsi</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Amount</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tipe</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Reconciled</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($reconciliations as $row)
                <tr>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->bankAccount?->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->statement_date }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $row->description ?? $row->reference ?? '-' }}<br><span class="muted">{{ $row->reference ?? '' }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $rp($row->amount) }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $row->type === 'credit' ? 'active' : 'pending' }}">{{ $row->type }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $row->reconciled ? 'active' : 'void' }}">{{ $row->reconciled ? 'Yes' : 'No' }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                        @if($can('admin', 'finance'))
                            <a href="{{ route('bank-reconciliation-items.edit-page', $row) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                            <form method="post" action="{{ route('bank-reconciliation-items.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus data rekonsiliasi ini?">
                            @csrf
                            @method('delete')
                            <button type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada reconciliation item.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$reconciliations" />
</div>

<x-erp.import-modal module="bank-reconciliation-items" title="Impor Data Bank Reconciliation Items" />
@endsection