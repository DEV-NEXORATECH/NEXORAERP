@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => 'Vendors & Suppliers'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Vendor & Supplier"
        subtitle="Kelola direktori mitra pemasok barang, lisensi software, syarat pembayaran, dan riwayat PO."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Vendors']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions
                module="vendors"
                :create-route="route('vendors.create-page')"
                create-label="+ Tambah Vendor"
                :can-create="$can('admin', 'finance')"
            />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('vendors.index') }}" class="filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nama vendor, PIC, email..." value="{{ request('search') }}"
                    class="filter-search !w-full pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>
            <select name="status" class="text-xs">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') == 'active')>Active</option>
                <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                <option value="blacklisted" @selected(request('status') == 'blacklisted')>Blacklisted</option>
            </select>
            <button type="submit" class="inline-flex h-[34px] items-center gap-1.5 rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors flex-shrink-0 cursor-pointer">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('vendors.index') }}" class="inline-flex h-[34px] items-center rounded-md border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors flex-shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Perusahaan Vendor</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">PIC & Kontak</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Payment Terms</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vendors as $row)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $row->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $row->category ?: 'General Supplier' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $row->contact_name ?: '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $row->email ?: '' }} {{ ($row->email && $row->phone) ? '·' : '' }} {{ $row->phone ?: '' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-700 font-mono">
                                {{ $row->payment_terms ?: 'Net 30' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $vBadge = match(strtolower($row->status)) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'blacklisted' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $vBadge }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('vendors.show', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if($can('admin', 'finance'))
                                        <a href="{{ route('vendors.edit-page', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('vendors.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus data vendor ini?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data vendor/supplier yang terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$vendors" />
</div>

<x-erp.import-modal module="vendors" title="Impor Data Vendor" />
@endsection

