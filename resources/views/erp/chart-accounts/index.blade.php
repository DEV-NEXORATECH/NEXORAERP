@extends('layouts.erp', ['activePage' => 'chart-accounts', 'pageTitle' => 'Chart of Accounts'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Bagan Akun (Chart of Accounts)</h1>
            <p class="text-xs text-[#475569]">Kelola master data dan transaksi Bagan Akun (Chart of Accounts)</p>
        </div>
        <x-erp.toolbar-actions module="chart-accounts" />
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
        <form method="get" action="{{ route('chart-accounts.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <select name="type" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Tipe</option>
            <option value="asset" @selected(request('type') == 'asset')>Asset</option>
            <option value="liability" @selected(request('type') == 'liability')>Liability</option>
            <option value="equity" @selected(request('type') == 'equity')>Equity</option>
            <option value="income" @selected(request('type') == 'income')>Income</option>
            <option value="expense" @selected(request('type') == 'expense')>Expense</option>
        </select>
        <select name="is_active" class="px-2.5 py-1.5 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('chart-accounts.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Code</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Name</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Type</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($accounts as $account)
                <tr>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $account->code }}</td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $account->name }}<br><span class="muted">{{ $account->parent?->code ? $account->parent->code.' - '.$account->parent->name : 'Root' }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge">{{ $account->type }}</span></td>
                    <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="badge badge-{{ $account->is_active ? 'active' : 'void' }}">{{ $account->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                        @if($can('admin', 'finance'))
                            <a data-action="edit" href="{{ route('chart-accounts.edit-page', $account) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                        @endif
                        @if($can('admin'))
                            <form method="post" action="{{ route('chart-accounts.destroy', $account) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus akun CoA ini?">
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
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada CoA.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$accounts" />
</div>

<x-erp.import-modal module="chart-accounts" title="Impor Data Chart Accounts" />
@endsection