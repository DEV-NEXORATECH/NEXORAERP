@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Purchase Requisition'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Purchase Requisition (PR)"
        subtitle="Permohonan pengadaan barang/jasa internal, approval departemen, dan alur konversi ke Purchase Order (PO)."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Purchase Requisitions']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="purchase-requisitions" :create-route="route('purchase-requisitions.create-page')" create-label="+ Buat PR Baru" />
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
        <form method="get" action="{{ route('purchase-requisitions.index') }}" class="filter-toolbar">
            
            <div>
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                    <option value="submitted" @selected(request('status') == 'submitted')>Submitted</option>
                    <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('purchase-requisitions.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
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
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Nomor PR</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Deskripsi & Keperluan</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Pemohon & Divisi</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Estimasi Anggaran</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requisitions as $row)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-[#002875]">
                                {{ $row->number }}
                            </td>
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $row->title }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $row->reason ?: 'Tanpa catatan' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $row->requester->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $row->department->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                {{ $rp($row->amount) }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $prBadge = match(strtolower($row->status)) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $prBadge }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-erp.action-buttons
                                    :edit-url="$can('admin', 'finance') ? route('purchase-requisitions.edit-page', $row) : null"
                                    :print-url="route('purchase-requisitions.pdf', $row)"
                                    :delete-url="$can('admin') ? route('purchase-requisitions.destroy', $row) : null"
                                    :record-name="'purchase requisition ' . $row->number"
                                    :can-edit="$can('admin', 'finance')"
                                    :can-delete="$can('admin')"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data purchase requisition yang diajukan." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($requisitions->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{ $requisitions->firstItem() }}-{{ $requisitions->lastItem() }} dari {{ $requisitions->total() }} requisition</span>
                {{ $requisitions->links() }}
        </div>
    @endif
</div>

<x-erp.import-modal module="purchase-requisitions" title="Impor Data Purchase Requisitions" />
@endsection