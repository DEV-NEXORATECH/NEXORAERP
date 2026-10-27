@extends('layouts.erp', ['activePage' => 'clients', 'pageTitle' => 'Clients'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Klien / Customer"
        subtitle="Kelola database partner komersial, data PIC, kontrak, dan riwayat relasi bisnis."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Klien']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions
                module="clients"
                :create-route="route('clients.create-page')"
                create-label="+ Tambah Klien"
                :can-create="$can('admin', 'sales', 'finance')"
            />
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
        <form method="get" action="{{ route('clients.index') }}" class="filter-toolbar erp-filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nama perusahaan, PIC, email..." value="{{ request('search') }}"
                    class="filter-search pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>
            <button type="submit" class="inline-flex h-[34px] items-center gap-1.5 rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors flex-shrink-0 cursor-pointer">
                Filter
            </button>
            @if(request('search'))
                <a href="{{ route('clients.index') }}" class="inline-flex h-[34px] items-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors flex-shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Perusahaan Klien</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">PIC & Kontak</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $client->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $client->address ?: 'Alamat belum diatur' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $client->contact_name ?: '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $client->email ?: '-' }} · {{ $client->phone ?: '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a style="color:#0F766E!important;background:#F0FDFA!important;border-color:#99F6E4!important" data-action="view" href="{{ route('clients.show', $client) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#99F6E4] bg-[#F0FDFA] text-[#0F766E] hover:bg-[#CCFBF1]" title="Lihat Detail">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if($can('admin', 'sales', 'finance'))
                                        <a style="color:#7C3AED!important;background:#F5F3FF!important;border-color:#DDD6FE!important" data-action="edit" href="{{ route('clients.edit-page', $client) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('clients.destroy', $client) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus klien" data-confirm-text="Apakah Anda yakin ingin menghapus data klien ini? Tindakan ini tidak dapat dibatalkan.">
                                                @csrf
                                                @method('delete')
                                                <button style="color:#DC2626!important;background:#FEF2F2!important;border-color:#FECACA!important" data-action="delete" type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data klien terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$clients" />
</div>

<x-erp.import-modal module="clients" title="Impor Data Klien" />
@endsection

