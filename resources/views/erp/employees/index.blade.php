@extends('layouts.erp', ['activePage' => 'employees', 'pageTitle' => 'Employees'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#E5E7EB] shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">HR & Organisasi</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#334155]">Karyawan</span>
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
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('hr.index') }}" class="filter-toolbar erp-filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nama karyawan..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
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
                        <tr class="hover:bg-[#F3F4F6] transition">
                            <td class="px-4 py-3 font-bold text-[#0F172A]">
                                <a href="{{ route('employees.show', $employee) }}" class="text-[#002875] hover:underline">
                                    {{ $employee->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-[#475569]">{{ $employee->department }}</td>
                            <td class="px-4 py-3 font-medium text-[#0F172A]">{{ $employee->position }}</td>
                            <td class="px-4 py-3 text-right font-bold text-[#15803D]">{{ $rp($employee->base_salary) }}</td>
                            <td class="px-4 py-3 text-right">
                                <x-erp.action-buttons
                                    :show-url="route('employees.show', $employee)"
                                    :edit-url="$can('admin', 'hr') ? route('employees.edit-page', $employee) : null"
                                    :delete-url="$can('admin') ? route('employees.destroy', $employee) : null"
                                    :record-name="'karyawan ' . $employee->name"
                                    :can-edit="$can('admin', 'hr')"
                                    :can-delete="$can('admin')"
                                />
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

