@extends('layouts.erp', ['activePage' => 'sales-leads.index', 'pageTitle' => 'Sales Leads Pipeline'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Pipeline Prospek Penjualan (Leads)"
        subtitle="Pantau tahap deal prospek komersial, bobot probabilitas, dan proyeksi nilai konversi penjualan."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Leads']]"
    >
        <x-slot:actions>
            @if($can('admin', 'sales'))
                <a href="{{ route('sales-leads.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Lead Baru
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('sales-leads.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" placeholder="Cari judul lead, prospek..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="stage" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Stage</option>
                    <option value="qualified" @selected(request('stage') === 'qualified')>Qualified</option>
                    <option value="proposal" @selected(request('stage') === 'proposal')>Proposal</option>
                    <option value="negotiation" @selected(request('stage') === 'negotiation')>Negotiation</option>
                    <option value="won" @selected(request('stage') === 'won')>Won</option>
                    <option value="lost" @selected(request('stage') === 'lost')>Lost</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('sales-leads.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Judul Peluang (Deal)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Tahap Pipeline</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Potensi Nilai (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-center">Probabilitas</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Target Closing</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $row)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $row->title }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $stageBadge = match(strtolower($row->stage)) {
                                        'won' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'negotiation' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'proposal' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'lost' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $stageBadge }}">
                                    {{ strtoupper($row->stage) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                {{ $rp($row->value) }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-slate-700">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px]">
                                    {{ $row->probability }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                {{ $row->expected_close_date ? \Carbon\Carbon::parse($row->expected_close_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'sales'))
                                        <a href="{{ route('sales-leads.edit-page', $row) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('sales-leads.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus lead pipeline ini?');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
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
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data sales lead dalam pipeline." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }} lead</span>
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
