@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Sales & Proposals'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Dokumen Proposal & Penawaran"
        subtitle="Kelola dokumen penawaran harga komersial, status approval, verifikasi tandatangan, dan pencetakan PDF resmi."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Proposals']]"
    >
        <x-slot:actions>
            @if($can('admin', 'sales'))
                <a href="{{ route('proposals.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Buat Proposal Baru
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('sales.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" placeholder="Cari nomor atau judul proposal..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="status" class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="sent" @selected(request('status') === 'sent')>Sent</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
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
                <a href="{{ route('sales.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
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
                                    <a href="{{ route('proposals.pdf', $proposal) }}" target="_blank" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Cetak PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                                        PDF
                                    </a>
                                    <a href="{{ route('proposals.edit-page', $proposal) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        Edit
                                    </a>
                                    @if($can('admin'))
                                        <form method="post" action="{{ route('proposals.destroy', $proposal) }}" class="inline" onsubmit="return confirm('Hapus proposal ini?');">
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
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data dokumen proposal." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($proposalsPage->hasPages() || $proposalsPage->total() > 0)
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $proposalsPage->firstItem() }}-{{ $proposalsPage->lastItem() }} dari {{ $proposalsPage->total() }} proposal</span>
                {{ $proposalsPage->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
