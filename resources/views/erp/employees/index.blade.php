@extends('layouts.erp', ['activePage' => 'employees', 'pageTitle' => 'Employees'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#E5E7EB] shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">HR & Organisasi</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-gray-700">Karyawan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Data Karyawan (Employees)</h1>
            <p class="mt-0.5 text-xs text-[#64748B]">Kelola data personal karyawan, penempatan departemen, struktur jabatan, dan profil kepegawaian.</p>
        </div>
        <x-erp.toolbar-actions
            module="employees"
            :create-route="route('employees.create-page')"
            create-label="+ Tambah Karyawan"
            :can-create="$can('admin', 'hr')"
        />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('hr.index') }}" class="filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nama karyawan..." value="{{ request('search') }}" class="filter-search !w-full pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>

            <select name="department_id" class="text-xs">
                <option value="">Semua Departemen</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>

            <select name="job_position_id" class="text-xs">
                <option value="">Semua Posisi Jabatan</option>
                @foreach($jobPositions as $position)
                    <option value="{{ $position->id }}" @selected(request('job_position_id') == $position->id)>{{ $position->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex h-[34px] items-center justify-center rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-xs cursor-pointer flex-shrink-0">
                Filter
            </button>

            @if(request()->hasAny(['search', 'department_id', 'job_position_id']))
                <a href="{{ route('hr.index') }}" class="inline-flex h-[34px] items-center justify-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#475569] hover:bg-[#F3F4F6] transition shadow-xs flex-shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card bg-white rounded-lg border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Nama Karyawan</th>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Departemen</th>
                        <th class="px-4 py-3 font-semibold text-[#475569]">Jabatan</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Gaji Pokok</th>
                        <th class="px-4 py-3 font-semibold text-[#475569] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($employeesPage as $employee)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-bold text-[#0F172A]">
                                <a href="{{ route('employees.show', $employee) }}" class="text-[#002875] hover:underline">
                                    {{ $employee->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-[#475569]">{{ $employee->department }}</td>
                            <td class="px-4 py-3 font-medium text-[#0F172A]">{{ $employee->position }}</td>
                            <td class="px-4 py-3 text-right font-bold text-[#15803D]">{{ $rp($employee->base_salary) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Eye / Show --}}
                                    <a href="{{ route('employees.show', $employee) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Profil">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    {{-- Edit --}}
                                    @if($can('admin', 'hr'))
                                        <a href="{{ route('employees.edit-page', $employee) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    @if($can('admin'))
                                        <form method="post" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Hapus karyawan {{ $employee->name }}?');">
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
                            <td colspan="5" class="py-8 text-center text-[#94A3B8]">Belum ada karyawan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$employeesPage" />
</div>

<x-erp.import-modal module="employees" title="Impor Data Karyawan" />
@endsection

