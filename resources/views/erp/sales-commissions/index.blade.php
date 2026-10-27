@extends('layouts.erp', ['activePage' => 'sales-commissions.index', 'pageTitle' => 'Sales Commissions'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Komisi Penjualan Sales"
        subtitle="Kelola perhitungan insentif komisi berdasarkan realisasi transaksi penjualan yang disetujui."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Commissions']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="sales-commissions" :create-route="route('sales-commissions.create-page')" create-label="+ Hitung Komisi Baru" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('sales-commissions.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            <div>
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                </select>
            </div>
            <div>
                <select name="user_id" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Sales</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('sales-commissions.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    Reset
                </a>
            </div>
        
        </form>
    </div>

    <div class="rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Sales Representative</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Periode</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Nilai Dasar Deal (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-center">Rate</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Jumlah Komisi (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $row->user->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-700 font-mono">
                                {{ $row->period }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-slate-900">
                                {{ $rp($row->base_amount) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                    {{ $row->rate }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                {{ $rp($row->commission_amount) }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $commBadge = match(strtolower($row->status)) {
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $commBadge }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'sales'))
                                        <a href="{{ route('sales-commissions.edit-page', $row) }}" class="erp-action-button inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('sales-commissions.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus data komisi ini?">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="erp-action-button inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada perhitungan komisi penjualan." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$items" />
</div>

<x-erp.import-modal module="sales-commissions" title="Impor Data Sales Commissions" />
@endsection