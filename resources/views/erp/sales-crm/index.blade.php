@extends('layouts.erp', ['activePage' => 'sales-crm', 'pageTitle' => 'Sales & CRM Central'])

@section('content')
@php
    $pipelineValue = $leads->getCollection()->sum('value');
    $orderValue = $orders->getCollection()->sum('amount');
@endphp

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#E8EEF5] text-[#002875]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Commercial & Revenue</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">CRM Overview</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Sales & CRM Central</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Monitor pipeline penjualan, leads, sales orders, target performa sales, dan siklus kontrak klien.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('sales-leads.create-page') }}" class="btn btn-primary inline-flex h-9 items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Lead Baru
            </a>
            <a href="{{ route('sales-orders.create-page') }}" class="btn btn-outline inline-flex h-9 items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Sales Order
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Inquiries Masuk</div>
            <div class="text-2xl font-bold text-[#0F172A] mt-1">{{ $inquiries->total() }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Leads masuk dari channel aktif</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Pipeline Value</div>
            <div class="text-2xl font-bold text-[#15803D] mt-1">{{ $rp($pipelineValue) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Total potensi lead berjalan</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Sales Order</div>
            <div class="text-2xl font-bold text-[#002875] mt-1">{{ $orders->total() }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Total nilai {{ $rp($orderValue) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Kontrak Aktif</div>
            <div class="text-2xl font-bold text-[#0F172A] mt-1">{{ $contracts->total() }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Lifecycle kontrak klien terdaftar</div>
        </div>
    </div>

    {{-- Section 1: Inquiries & Leads (2 Columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card: Inquiries --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Inquiry Masuk</h2>
                        <p class="text-xs text-[#475569]">Permintaan prospek dari website dan referral</p>
                    </div>
        <a href="{{ route('sales-leads.create-page') }}" class="inline-flex h-8 items-center rounded-md bg-[#002875] px-3 text-xs font-semibold text-white hover:bg-[#001B50]">+ Lead Baru</a>
    </div>

                <div class="overflow-x-auto">
                    <table class="table-folk w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                                <th class="p-2.5 font-semibold text-[#475569]">Perusahaan / Kontak</th>
                                <th class="p-2.5 font-semibold text-[#475569]">Kebutuhan</th>
                                <th class="p-2.5 font-semibold text-[#475569] text-center">Status</th>
                                <th class="p-2.5 font-semibold text-[#475569] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse($inquiries as $row)
                                <tr class="hover:bg-[#F3F4F6] transition">
                                    <td class="p-2.5">
                                        <p class="font-bold text-[#0F172A]">{{ $row->company_name }}</p>
                                        <p class="text-[11px] text-[#64748B]">{{ $row->contact_name ?: '-' }}</p>
                                    </td>
                                    <td class="p-2.5 text-[#334155]">{{ $row->need ?? '-' }}</td>
                                    <td class="p-2.5 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $row->status === 'lost' ? 'bg-red-50 text-red-700 border border-red-200' : ($row->status === 'new' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <a href="{{ route('sales-inquiries.edit-page', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-[#94A3B8]">Belum ada inquiry.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($inquiries->hasPages())
                <div class="pt-3 border-t border-[#E5E7EB] mt-3">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>

        {{-- Card: Leads & Pipeline --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Lead & Pipeline</h2>
                        <p class="text-xs text-[#475569]">Peluang bisnis aktif dan probabilitas konversi</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('sales-leads.create-page') }}" class="btn btn-primary inline-flex h-8 items-center px-2.5 text-xs font-semibold">
                            + Tambah Lead
                        </a>
                        <a href="{{ route('sales-leads.index') }}" class="text-xs font-semibold text-[#002875] hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                                <th class="p-2.5 font-semibold text-[#475569]">Judul Peluang</th>
                                <th class="p-2.5 font-semibold text-[#475569] text-center">Stage</th>
                                <th class="p-2.5 font-semibold text-[#475569] text-right">Potensi Nilai</th>
                                <th class="p-2.5 font-semibold text-[#475569] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse($leads as $row)
                                <tr class="hover:bg-[#F3F4F6] transition">
                                    <td class="p-2.5">
                                        <p class="font-bold text-[#0F172A]">{{ $row->title }}</p>
                                        <p class="text-[11px] text-[#64748B]">{{ $row->probability }}% probabilitas</p>
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $row->stage === 'lost' ? 'bg-red-50 text-red-700 border border-red-200' : ($row->stage === 'won' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-[#002875] border border-blue-200') }}">
                                            {{ $row->stage }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-right font-bold text-[#0F172A]">{{ $rp($row->value) }}</td>
                                    <td class="p-2.5 text-right">
                                        <a href="{{ route('sales-leads.edit-page', $row) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-[#94A3B8]">Belum ada data lead.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($leads->hasPages())
                <div class="pt-3 border-t border-[#E5E7EB] mt-3">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Section 2: Sales Orders, Targets, Contracts (3 Columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Card: Sales Orders --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Sales Order (SO)</h2>
                        <p class="text-xs text-[#475569]">Order penjualan tervalidasi</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('sales-orders.create-page') }}" class="btn btn-primary inline-flex h-7 items-center px-2 text-[11px] font-semibold">
                            + SO
                        </a>
                        <a href="{{ route('sales-orders.index') }}" class="text-[11px] font-semibold text-[#002875] hover:underline">
                            Semua &rarr;
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-[#E5E7EB] overflow-y-auto max-h-72">
                    @forelse($orders as $row)
                        <div class="py-2.5 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#0F172A] truncate">{{ $row->number }}</p>
                                <p class="text-[11px] font-semibold text-[#15803D]">{{ $rp($row->amount) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $row->status === 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $row->status }}
                                </span>
                                <a href="{{ route('sales-orders.edit-page', $row) }}" class="inline-flex h-6 w-6 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[#94A3B8] py-4 text-center">Belum ada Sales Order.</p>
                    @endforelse
                </div>
            </div>
            @if($orders->hasPages())
                <div class="pt-2 border-t border-[#E5E7EB] mt-2">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        {{-- Card: Target & Komisi --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Target & Komisi</h2>
                        <p class="text-xs text-[#475569]">Target penjualan & komisi tim</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('sales-targets.create-page') }}" class="btn btn-primary inline-flex h-7 items-center px-2 text-[11px] font-semibold">
                            + Target
                        </a>
                        <a href="{{ route('sales-commissions.create-page') }}" class="btn btn-outline inline-flex h-7 items-center px-2 text-[11px] font-semibold">
                            + Komisi
                        </a>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#475569]">Target Penjualan</span>
                            <a href="{{ route('sales-targets.index') }}" class="text-[10px] font-semibold text-[#002875] hover:underline">Semua Target &rarr;</a>
                        </div>
                        <div class="divide-y divide-[#E5E7EB] overflow-y-auto max-h-32">
                            @forelse($targets as $row)
                                <div class="py-1.5 flex items-center justify-between text-xs">
                                    <span class="text-[#0F172A] font-medium">{{ $row->period }}</span>
                                    <span class="font-bold text-[#002875]">{{ $rp($row->target_amount) }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-[#94A3B8] py-2 text-center">Belum ada target.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="border-t border-[#E5E7EB] pt-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#475569]">Komisi Terkini</span>
                            <a href="{{ route('sales-commissions.index') }}" class="text-[10px] font-semibold text-[#002875] hover:underline">Semua Komisi &rarr;</a>
                        </div>
                        <div class="divide-y divide-[#E5E7EB] overflow-y-auto max-h-32">
                            @forelse($commissions as $row)
                                <div class="py-1.5 flex items-center justify-between text-xs">
                                    <span class="text-[#0F172A] font-medium">{{ $row->period }}</span>
                                    <span class="font-semibold text-emerald-700">{{ $row->rate }}% ({{ $row->status }})</span>
                                </div>
                            @empty
                                <p class="text-xs text-[#94A3B8] py-2 text-center">Belum ada komisi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Client Contracts --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB] mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-[#0F172A]">Kontrak Klien</h2>
                        <p class="text-xs text-[#475569]">Perjanjian kerja & legal contract</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('client-contracts.create-page') }}" class="btn btn-primary inline-flex h-7 items-center px-2 text-[11px] font-semibold">
                            + Kontrak
                        </a>
                        <a href="{{ route('client-contracts.index') }}" class="text-[11px] font-semibold text-[#002875] hover:underline">
                            Semua &rarr;
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-[#E5E7EB] overflow-y-auto max-h-72">
                    @forelse($contracts as $row)
                        <div class="py-2.5 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#0F172A] truncate">{{ $row->contract_number }}</p>
                                <p class="text-[11px] text-[#475569] truncate">{{ $row->title }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $row->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $row->status }}
                                </span>
                                <a href="{{ route('client-contracts.edit-page', $row) }}" class="inline-flex h-6 w-6 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[#94A3B8] py-4 text-center">Belum ada kontrak klien.</p>
                    @endforelse
                </div>
            </div>
            @if($contracts->hasPages())
                <div class="pt-2 border-t border-[#E5E7EB] mt-2">
                    {{ $contracts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<x-erp.import-modal module="sales-crm" title="Impor Data Sales Crm" />
@endsection