@extends('layouts.erp', ['activePage' => 'reimbursements', 'pageTitle' => 'Reimbursement #'.$reimbursement->id])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('reimbursements.index-page') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Finance</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Reimbursements</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">Klaim #{{ $reimbursement->id }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $reimbursement->employee->name ?? 'Karyawan' }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $reimbursement->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($reimbursement->status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-[#002875] border border-blue-200') }}">
                        {{ $reimbursement->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('reimbursements.edit-page', $reimbursement) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Klaim
            </a>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="max-w-2xl rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
        <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Informasi Pengajuan Klaim</h2>
        <dl class="space-y-3 text-xs">
            <div>
                <dt class="text-[#64748B]">Karyawan Pemohon</dt>
                <dd class="font-bold text-[#0F172A] mt-0.5">{{ $reimbursement->employee->name ?? '-' }} ({{ $reimbursement->employee->department->name ?? 'Operasional' }})</dd>
            </div>
            <div>
                <dt class="text-[#64748B]">Kategori Pengeluaran</dt>
                <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $reimbursement->category ?: 'Operasional' }}</dd>
            </div>
            <div>
                <dt class="text-[#64748B]">Tanggal Pengajuan</dt>
                <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $reimbursement->claim_date ? \Carbon\Carbon::parse($reimbursement->claim_date)->format('d M Y') : '-' }}</dd>
            </div>
            <div>
                <dt class="text-[#64748B]">Proyek Terkait</dt>
                <dd class="font-medium text-[#0F172A] mt-0.5">{{ $reimbursement->project->code ?? 'Non-proyek' }}</dd>
            </div>
            <div class="pt-2 border-t border-[#E5E7EB]">
                <dt class="text-[#64748B]">Jumlah Klaim Biaya</dt>
                <dd class="text-2xl font-bold text-[#15803D] mt-1">{{ $rp($reimbursement->amount) }}</dd>
            </div>
            <div class="pt-2 border-t border-[#E5E7EB]">
                <dt class="text-[#64748B]">Keterangan Pengeluaran</dt>
                <dd class="font-normal text-[#334155] mt-1">{{ $reimbursement->notes ?: 'Tidak ada keterangan tambahan.' }}</dd>
            </div>
            @if($reimbursement->receipt_file_path)
                <div class="pt-2 border-t border-[#E5E7EB]">
                    <dt class="text-[#64748B]">Bukti Struk / Kuitansi</dt>
                    <dd class="mt-1">
                        <a href="{{ asset('storage/'.$reimbursement->receipt_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#002875] hover:underline">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Lihat Dokumen Struk
                        </a>
                    </dd>
                </div>
            @endif
        </dl>
    </div>
</div>
@endsection