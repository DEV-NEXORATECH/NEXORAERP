@extends('layouts.erp', ['activePage' => 'client-contracts.index', 'pageTitle' => 'Client Contracts'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Kontrak Kerja Sama Klien"
        subtitle="Kelola dokumen kontrak legal klien, jangka waktu berlaku, nilai kerja sama, dan reminder perpanjangan."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Client Contracts']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="client-contracts" :create-route="route('client-contracts.create-page')" create-label="+ Tambah Kontrak Baru" />
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
        <form method="get" action="{{ route('client-contracts.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            <div>
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                    <option value="terminated" @selected(request('status') === 'terminated')>Terminated</option>
                </select>
            </div>
            <div>
                <select name="client_id" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Klien</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" @selected(request('client_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('client-contracts.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
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
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Judul Kontrak</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Nomor Dokumen</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Klien</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Periode Efektif</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Nilai Kontrak (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $row->title }}
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-700">
                                {{ $row->contract_number }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 font-medium">
                                {{ $row->client->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-[11px]">
                                {{ $row->start_date ? \Carbon\Carbon::parse($row->start_date)->format('d M Y') : '-' }} — {{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                {{ $rp($row->amount) }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $contractBadge = match(strtolower($row->status)) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'expired' => 'bg-slate-100 text-slate-600 border-[#D1D5DB]',
                                        'terminated' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $contractBadge }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1 justify-end">
                                    <a data-action="print" href="{{ route('client-contracts.pdf', $row) }}" target="_blank" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Cetak Kontrak PDF">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    @if($can('admin', 'sales'))
                                        <a data-action="edit" href="{{ route('client-contracts.edit-page', $row) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>
                                        <form method="post" action="{{ route('client-contracts.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus data kontrak ini?">
                                            @csrf
                                            @method('delete')
                                            <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data kontrak klien yang tercatat." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$items" />
</div>

<x-erp.import-modal module="client-contracts" title="Impor Data Client Contracts" />
@endsection