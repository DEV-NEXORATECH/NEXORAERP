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

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('sales.index') }}" class="filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nomor atau judul proposal..." value="{{ request('search') }}"
                    class="filter-search pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>

            <select name="status" class="text-xs">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="sent" @selected(request('status') === 'sent')>Sent</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs" title="Dari Tanggal">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs" title="Sampai Tanggal">

            <button type="submit" class="inline-flex h-[34px] items-center gap-1.5 rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors flex-shrink-0 cursor-pointer">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('sales.index') }}" class="inline-flex h-[34px] items-center rounded-md border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors flex-shrink-0">
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
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Proposal & Proyek</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status Dokumen</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Nilai Penawaran (Rp)</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposalsPage as $proposal)
                        <tr class="hover:bg-slate-50/70 transition-colors">
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
                                    <select name="status" class="rounded border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-800 focus:border-[#002875] focus:outline-none">
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
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('proposals.show', $proposal) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('proposals.edit-page', $proposal) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </a>
                                    <a href="{{ route('proposals.pdf', $proposal) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-white text-[#475569] hover:bg-[#F3F4F6] hover:text-[#002875]" title="Cetak PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                                    </a>
                                    @if($can('admin'))
                                        <form method="post" action="{{ route('proposals.destroy', $proposal) }}" class="inline" onsubmit="return confirm('Hapus proposal ini?');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
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

