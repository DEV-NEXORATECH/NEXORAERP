@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Sales & Proposals'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Dokumen Proposal & Penawaran"
        subtitle="Kelola dokumen penawaran harga komersial, status approval, verifikasi tandatangan, dan pencetakan PDF resmi."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Proposals']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions
                module="sales"
                :create-route="route('proposals.create-page')"
                create-label="+ Buat Proposal"
                :can-create="$can('admin', 'sales')"
            />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Panel --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form id="proposal-filter" method="get" action="{{ route('sales.index') }}">
            <div class="filter-grid">
                <div class="filter-field sm:col-span-2">
                    <label for="search">Pencarian</label>
                    <input id="search" type="text" name="search" placeholder="Cari nomor atau judul proposal..." value="{{ request('search') }}">
                </div>
                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="sent" @selected(request('status') === 'sent')>Sent</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label for="date_from">Dari Tanggal</label>
                    <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-field">
                    <label for="date_to">Sampai Tanggal</label>
                    <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>
        </form>
        <div class="filter-actions">
            <div class="filter-actions-main">
                <button type="submit" form="proposal-filter" class="filter-btn-apply">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('sales.index') }}" class="filter-btn-reset">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Proposal & Proyek</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status Dokumen</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Nilai Penawaran (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposalsPage as $proposal)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $proposal->title }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $proposal->number ? $proposal->number.' · ' : '' }}{{ $proposal->project->code ?? 'Non-Project' }} ({{ $proposal->project->name ?? '-' }})
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <form method="post" action="{{ route('proposals.status', $proposal) }}" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('patch')
                                    <select name="status" class="rounded border border-[#D1D5DB] bg-white px-2 py-0.5 text-xs text-slate-800 focus:border-[#002875] focus:outline-none">
                                        @foreach($statusOptions['proposal'] as $status)
                                            <option @selected($proposal->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-[#002875] hover:bg-slate-200 transition-colors">
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                {{ $rp($proposal->amount) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-erp.action-buttons
                                    :show-url="route('proposals.show', $proposal)"
                                    :edit-url="route('proposals.edit-page', $proposal)"
                                    :print-url="route('proposals.pdf', $proposal)"
                                    :delete-url="$can('admin') ? route('proposals.destroy', $proposal) : null"
                                    :record-name="'proposal ' . ($proposal->number ?? $proposal->title)"
                                    :can-delete="$can('admin')"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data dokumen proposal." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$proposalsPage" />
</div>

<x-erp.import-modal module="sales" title="Impor Data Proposal & Penawaran" />
@endsection

