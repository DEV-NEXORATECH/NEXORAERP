@extends('layouts.erp', ['activePage' => 'sales-inquiries.index', 'pageTitle' => 'Sales Inquiries'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Sales Inquiry"
        subtitle="Kelola permintaan prospek awal, kualifikasi kebutuhan klien, dan assign PIC sales."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Inquiries']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="sales-inquiries" :create-route="route('sales-inquiries.create-page')" create-label="+ Catat Inquiry Baru" />
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
        <form method="get" action="{{ route('sales-inquiries.index') }}" class="filter-toolbar erp-filter-toolbar">
            
            <div>
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="new" @selected(request('status') === 'new')>New</option>
                    <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
                    <option value="qualified" @selected(request('status') === 'qualified')>Qualified</option>
                    <option value="lost" @selected(request('status') === 'lost')>Lost</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('sales-inquiries.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
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
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Perusahaan & Kebutuhan</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Kontak PIC</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Sumber</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $row->company_name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $row->need ?: 'Belum ada deskripsi kebutuhan' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $row->contact_name ?: '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $row->email ?: '' }} {{ ($row->email && $row->phone) ? '·' : '' }} {{ $row->phone ?: '' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $row->source ?: '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $inquiryBadge = match(strtolower($row->status)) {
                                        'qualified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'contacted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'lost' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $inquiryBadge }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'sales'))
                                        <a href="{{ route('sales-inquiries.edit-page', $row) }}" class="erp-action-button inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('sales-inquiries.destroy', $row) }}" class="inline inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus data inquiry ini?">
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data sales inquiry yang tercatat." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-erp.pagination :paginator="$items" />
</div>

<x-erp.import-modal module="sales-inquiries" title="Impor Data Sales Inquiries" />
@endsection