@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Purchase Requisition'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Purchase Requisition (PR)"
        subtitle="Permohonan pengadaan barang/jasa internal, approval departemen, dan alur konversi ke Purchase Order (PO)."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Procurement', 'url' => route('procurement.index')], ['label' => 'Purchase Requisitions']]"
    >
        <x-slot:actions>
            @if($can('admin', 'finance'))
                <a href="{{ route('purchase-requisitions.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Buat PR Baru
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('purchase-requisitions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" placeholder="Cari nomor PR, judul pengadaan..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                    <option value="submitted" @selected(request('status') == 'submitted')>Submitted</option>
                    <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('purchase-requisitions.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
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
                        <tr class="hover:bg-slate-50/70 transition-colors">
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
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'finance'))
                                        <a href="{{ route('purchase-requisitions.edit-page', $row) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        @if($can('admin'))
                                            <form method="post" action="{{ route('purchase-requisitions.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus purchase requisition ini?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
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

        @if($requisitions->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $requisitions->firstItem() }}-{{ $requisitions->lastItem() }} dari {{ $requisitions->total() }} requisition</span>
                {{ $requisitions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
