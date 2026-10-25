@extends('layouts.erp', ['activePage' => 'salaries', 'pageTitle' => 'Slip Gaji '.($salary->slip_number ?: $salary->id)])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('salaries.index-page') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">HR & Payroll</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Salaries</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $salary->slip_number ?: 'SLP-NX'.$salary->id }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Slip Gaji — {{ $salary->employee->name ?? 'Karyawan' }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $salary->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-[#002875] border border-blue-200' }}">
                        {{ $salary->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('salaries.pdf', $salary) }}" target="_blank" class="btn btn-outline inline-flex h-9 items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
                <svg class="h-4 w-4 text-[#002875]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Slip
            </a>
            <a href="{{ route('salaries.edit-page', $salary) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Slip
            </a>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="max-w-3xl rounded-lg border border-[#E5E7EB] bg-white p-6 shadow-xs">
        <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Rincian Komponen Penghasilan & Potongan (Periode: {{ $salary->period }})</h2>
        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[#64748B]">Karyawan:</span>
                    <p class="font-bold text-[#0F172A] text-sm mt-0.5">{{ $salary->employee->name ?? '-' }}</p>
                    <p class="text-[11px] text-[#64748B]">{{ $salary->employee->jobPosition->name ?? 'Staf' }} &bull; {{ $salary->employee->department->name ?? 'Operasional' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-[#64748B]">Tanggal Pembayaran:</span>
                    <p class="font-semibold text-[#0F172A] mt-0.5">{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : 'Belum dibayarkan' }}</p>
                </div>
            </div>

            <div class="border-t border-[#E5E7EB] pt-4 space-y-2.5">
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#475569]">Gaji Pokok (Base Salary)</span>
                    <span class="font-bold text-[#0F172A]">{{ $rp($salary->base_salary) }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#475569]">Tunjangan (Allowance)</span>
                    <span class="font-bold text-[#15803D]">+ {{ $rp($salary->allowance) }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#475569]">Potongan (Deduction)</span>
                    <span class="font-bold text-[#DC2626]">- {{ $rp($salary->deduction) }}</span>
                </div>
                <div class="border-t-2 border-[#E5E7EB] pt-3 flex justify-between items-center">
                    <span class="text-sm font-bold text-[#0F172A]">Gaji Bersih Diterima (Take Home Pay)</span>
                    <span class="text-xl font-bold text-[#002875]">{{ $rp($salary->net_salary) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection