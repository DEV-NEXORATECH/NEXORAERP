@extends('layouts.erp', ['activePage' => 'attendances', 'pageTitle' => 'Presensi & Kehadiran Karyawan'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Presensi & Kehadiran Karyawan"
        subtitle="Pantau jam masuk, jam keluar, mode kerja (WFO / WFH / Remote), serta status kehadiran harian."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Attendances']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="attendances" :create-route="route('attendances.create-page')" create-label="Catat Presensi" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('attendances.index') }}" class="filter-toolbar erp-filter-toolbar">
            <input type="text" name="search" placeholder="Cari data..." value="{{ request('search') }}" class="filter-search">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <select name="employee_id" class="filter-select">
                <option value="">Semua Karyawan</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>
            <select name="mode" class="filter-select">
                <option value="">Semua Mode</option>
                <option value="office" @selected(request('mode') === 'office')>Office (WFO)</option>
                <option value="remote" @selected(request('mode') === 'remote')>Remote (WFH)</option>
                <option value="hybrid" @selected(request('mode') === 'hybrid')>Hybrid</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to', 'employee_id', 'mode']))
                    <a href="{{ route('attendances.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-[#D1D5DB] bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Tanggal Kerja</th>
                        <th class="py-3 px-4 text-center">Check-In</th>
                        <th class="py-3 px-4 text-center">Check-Out</th>
                        <th class="py-3 px-4 text-center">Mode Kerja</th>
                        <th class="py-3 px-4 text-center">Status Kehadiran</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/60 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $row->employee->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $row->work_date ? \Carbon\Carbon::parse($row->work_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center font-mono font-medium text-slate-700">{{ $row->check_in ?? '--:--' }}</td>
                            <td class="py-3 px-4 text-center font-mono font-medium text-slate-700">{{ $row->check_out ?? '--:--' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 capitalize">
                                    {{ $row->work_mode ?? 'Office' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$row->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'hr'))
                                    <a data-action="edit" href="{{ route('attendances.edit-page', $row) }}"
                                        class="erp-action-button rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#7C3AED] transition-colors" title="Edit Presensi">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('attendances.destroy', $row) }}', 'Presensi {{ $row->employee->name ?? 'Karyawan' }} ({{ $row->work_date }})')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Presensi">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-erp.empty-state message="Belum ada data absensi presensi." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$items" />
</div>

<x-erp.confirm-dialog />
@endsection