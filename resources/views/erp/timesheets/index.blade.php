@extends('layouts.erp', ['activePage' => 'timesheets', 'pageTitle' => 'Timesheet Kerja Proyek'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Timesheet Kerja Proyek"
        subtitle="Pencatatan alokasi jam kerja harian staf untuk tiap task dan project client guna perhitungan cost rate."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Timesheets']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="timesheets" :create-route="route('timesheets.create-page')" create-label="Catat Timesheet" />
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
        <form method="get" action="{{ route('timesheets.index') }}" class="filter-toolbar erp-filter-toolbar">
            <input type="text" name="search" placeholder="Cari task / aktivitas..." value="{{ request('search') }}" class="filter-search">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" @selected(request('project_id') == $proj->id)>{{ $proj->code }} - {{ $proj->name }}</option>
                @endforeach
            </select>
            <select name="employee_id" class="filter-select">
                <option value="">Semua Karyawan</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'project_id', 'employee_id']))
                    <a href="{{ route('timesheets.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
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
                        <th class="py-3 px-4">Proyek / Task</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Durasi Jam</th>
                        <th class="py-3 px-4 text-center">Billable</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/60 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $row->employee->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-900">{{ $row->project->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $row->task_name }}</div>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">{{ $row->date ? \Carbon\Carbon::parse($row->date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center font-bold text-[#002875]">{{ $row->hours }} Jam</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $row->is_billable ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $row->is_billable ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$row->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'hr'))
                                    <a data-action="edit" href="{{ route('timesheets.edit-page', $row) }}"
                                        class="erp-action-button rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Timesheet">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('timesheets.destroy', $row) }}', 'Timesheet {{ $row->employee->name ?? 'Karyawan' }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Timesheet">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-erp.empty-state message="Belum ada data timesheet pengerjaan project." />
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