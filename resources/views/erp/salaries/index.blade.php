@extends('layouts.erp', ['activePage' => 'salaries', 'pageTitle' => 'Salary Workflow'])

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#E5E7EB] shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">HR & Payroll</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-700">Penggajian</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Penggajian (Salaries & Payroll)</h1>
            <p class="mt-0.5 text-xs text-[#64748B]">Kelola data slip gaji, tunjangan, potongan, dan status pembayaran gaji karyawan.</p>
        </div>
        <x-erp.toolbar-actions
            module="salaries"
            :create-route="route('salaries.create-page')"
            create-label="+ Buat Slip Gaji"
            :can-create="$can('admin', 'hr')"
        />
    </div>

    {{-- Filter Toolbar Directly Under Header (1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('salaries.index-page') }}" class="filter-toolbar">
            <select name="status" class="h-9 rounded-md border border-[#D1D5DB] bg-white px-3 text-xs text-[#0F172A] focus:border-[#002875] focus:ring-1 focus:ring-[#002875]">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status')==='draft')>Draft</option>
                <option value="approved" @selected(request('status')==='approved')>Approved</option>
                <option value="paid" @selected(request('status')==='paid')>Paid</option>
            </select>

            <input type="text" name="period" placeholder="Periode (YYYY-MM)..." value="{{ request('period') }}" class="h-9 rounded-md border border-[#D1D5DB] bg-white px-3 text-xs text-[#0F172A] focus:border-[#002875] focus:ring-1 focus:ring-[#002875]">

            <select name="employee_id" class="h-9 rounded-md border border-[#D1D5DB] bg-white px-3 text-xs text-[#0F172A] focus:border-[#002875] focus:ring-1 focus:ring-[#002875]">
                <option value="">Semua Karyawan</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex h-9 items-center justify-center rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-xs cursor-pointer">
                Filter
            </button>

            @if(request()->hasAny(['status', 'period', 'employee_id']))
                <a href="{{ route('salaries.index-page') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#475569] hover:bg-[#F3F4F6] transition shadow-xs">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Data Table Container --}}
    <div class="bg-white rounded-lg border border-[#E5E7EB] overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Karyawan</th>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Periode</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-center">Status</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Gaji Bersih</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($salariesPage as $salary)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3">
                                <a href="{{ route('salaries.show', $salary) }}" class="font-bold text-[#002875] hover:underline">
                                    {{ $salary->employee->name ?? 'Karyawan' }}
                                </a>
                                <div class="text-[11px] text-[#64748B]">{{ $salary->slip_number ?: 'SLP-NX'.$salary->id }}</div>
                            </td>
                            <td class="px-4 py-3 font-medium text-[#0F172A]">{{ $salary->period }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $salary->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-[#002875] border border-blue-200' }}">
                                    {{ $salary->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-[#15803D]">{{ $rp($salary->net_salary) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Eye / Show --}}
                                    <a href="{{ route('salaries.show', $salary) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    {{-- Print / PDF --}}
                                    <a href="{{ route('salaries.pdf', $salary) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-white text-[#475569] hover:bg-[#F3F4F6] hover:text-[#002875]" title="Cetak Slip PDF">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                    </a>

                                    {{-- Edit --}}
                                    @if($can('admin', 'hr'))
                                        <a href="{{ route('salaries.edit-page', $salary) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    @if($can('admin'))
                                        <form method="post" action="{{ route('salaries.destroy', $salary) }}" class="inline" onsubmit="return confirm('Hapus slip gaji ini?');">
                                            @csrf @method('delete')
                                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#94A3B8]">Belum ada data penggajian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$salariesPage" />
</div>

<x-erp.import-modal module="salaries" title="Impor Data Penggajian" />
@endsection

