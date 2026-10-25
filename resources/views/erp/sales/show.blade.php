@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Proposal '.$proposal->number])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('sales.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Sales</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Proposals</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $proposal->number }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $proposal->title }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $proposal->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($proposal->status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-[#002875] border border-blue-200') }}">
                        {{ $proposal->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('proposals.pdf', $proposal) }}" target="_blank" class="btn btn-outline inline-flex h-9 items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
                <svg class="h-4 w-4 text-[#002875]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak PDF
            </a>
            <a href="{{ route('proposals.edit-page', $proposal) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Proposal
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Nilai Penawaran</div>
            <div class="text-2xl font-bold text-[#0F172A] mt-1">{{ $rp($proposal->amount) }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Total komersial proposal</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Masa Berlaku</div>
            <div class="text-xl font-bold text-[#0F172A] mt-1">{{ $proposal->valid_until ? \Carbon\Carbon::parse($proposal->valid_until)->format('d M Y') : 'Tanpa batas' }}</div>
            <div class="text-xs text-[#64748B] mt-0.5">Tenggat penerimaan klien</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#E5E7EB] shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#475569]">Dokumen Terlampir</div>
            <div class="text-sm font-bold text-[#002875] mt-2">
                @if($proposal->signed_file_path)
                    <a href="{{ asset('storage/'.$proposal->signed_file_path) }}" target="_blank" class="hover:underline flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Signed File
                    </a>
                @else
                    <span class="text-[#94A3B8]">Tidak ada lampiran</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
        <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Lingkup Pekerjaan & Ketentuan (Scope of Work)</h2>
        <div class="prose max-w-none text-xs text-[#334155] whitespace-pre-line leading-relaxed">
            {{ $proposal->scope ?: 'Tidak ada rincian lingkup tertulis.' }}
        </div>
    </div>
</div>
@endsection