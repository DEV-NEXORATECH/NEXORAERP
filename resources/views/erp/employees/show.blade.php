@extends('layouts.erp', ['activePage' => 'hr', 'pageTitle' => $employee->name.' Profile'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('hr.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">HR & Organisasi</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Karyawan</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-bold text-[#0F172A]">{{ $employee->name }}</span>
                </div>
                <div class="flex items-center gap-2.5 mt-0.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $employee->name }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase
                        {{ $employee->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                        {{ $employee->status }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('employees.edit-page', $employee) }}" class="inline-flex h-9 items-center gap-1.5 px-3.5 rounded-md text-xs font-semibold text-white bg-[#0369A1] hover:bg-[#0284C7] transition shadow-xs">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Karyawan
            </a>
        </div>
    </div>

    {{-- Details 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Profil & Posisi</h2>
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-[#64748B]">Departemen</dt>
                    <dd class="font-bold text-[#0F172A] mt-0.5">{{ $employee->department->name ?? $employee->department ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Jabatan</dt>
                    <dd class="font-bold text-[#002875] mt-0.5">{{ $employee->jobPosition->name ?? $employee->position ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Email Perusahaan</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $employee->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Nomor Telepon</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $employee->phone ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#64748B]">Tanggal Bergabung</dt>
                    <dd class="font-semibold text-[#0F172A] mt-0.5">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '-' }}</dd>
                </div>
                <div class="pt-2 border-t border-[#E5E7EB]">
                    <dt class="text-[#64748B]">Gaji Pokok</dt>
                    <dd class="text-xl font-bold text-[#15803D] mt-1">{{ $rp($employee->salary) }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <h2 class="text-sm font-bold text-[#0F172A] border-b border-[#E5E7EB] pb-3 mb-4">Riwayat Slip Gaji (Payroll Records)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC]">
                            <th class="p-2.5 font-semibold text-[#475569]">No Slip</th>
                            <th class="p-2.5 font-semibold text-[#475569]">Periode</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-right">Gaji Bersih</th>
                            <th class="p-2.5 font-semibold text-[#475569] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($employee->salaries as $sal)
                            <tr>
                                <td class="p-2.5 font-bold text-[#0F172A]">{{ $sal->slip_number ?: 'SLP-'.$sal->id }}</td>
                                <td class="p-2.5 text-[#475569]">{{ $sal->period }}</td>
                                <td class="p-2.5 text-right font-bold text-[#15803D]">{{ $rp($sal->net_salary) }}</td>
                                <td class="p-2.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $sal->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-[#002875]' }}">
                                        {{ $sal->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-[#94A3B8]">Belum ada riwayat slip gaji tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection