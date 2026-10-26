<?php

// 1. attendances/index.blade.php
$attendances = <<<'BLADE'
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
    <div class="filter-card">
        <form method="get" action="{{ route('attendances.index') }}" class="filter-toolbar">
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
                    <a href="{{ route('attendances.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
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
                        <tr class="hover:bg-slate-50/60 transition-colors">
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
                                    <a href="{{ route('attendances.edit-page', $row) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Presensi">
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
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/attendances/index.blade.php', $attendances);

// 2. leave-requests/index.blade.php
$leaveRequests = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'leave-requests', 'pageTitle' => 'Pengajuan Cuti & Izin Karyawan'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Pengajuan Cuti & Izin Karyawan"
        subtitle="Manajemen permohonan cuti tahunan, izin sakit, cuti khusus, serta persetujuan status izin."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Leave Requests']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="leave-requests" :create-route="route('leave-requests.create-page')" create-label="Ajukan Cuti Baru" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('leave-requests.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari data..." value="{{ request('search') }}" class="filter-search">
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>
            <select name="type" class="filter-select">
                <option value="">Semua Tipe Cuti</option>
                <option value="annual" @selected(request('type') === 'annual')>Cuti Tahunan (Annual)</option>
                <option value="sick" @selected(request('type') === 'sick')>Sakit (Sick)</option>
                <option value="unpaid" @selected(request('type') === 'unpaid')>Cuti Tanpa Gaji (Unpaid)</option>
                <option value="special" @selected(request('type') === 'special')>Cuti Khusus (Special)</option>
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
                @if(request()->hasAny(['search', 'status', 'type', 'employee_id']))
                    <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4 text-center">Tipe Cuti</th>
                        <th class="py-3 px-4 text-center">Mulai</th>
                        <th class="py-3 px-4 text-center">Selesai</th>
                        <th class="py-3 px-4 text-center">Durasi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($items as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $row->employee->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 capitalize">
                                    {{ $row->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">{{ $row->start_date ? \Carbon\Carbon::parse($row->start_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">{{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-slate-700">{{ $row->total_days }} Hari</td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$row->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'hr'))
                                    <a href="{{ route('leave-requests.edit-page', $row) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Pengajuan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('leave-requests.destroy', $row) }}', 'Pengajuan Cuti {{ $row->employee->name ?? 'Karyawan' }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Pengajuan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-erp.empty-state message="Belum ada data pengajuan cuti & izin." />
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
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/leave-requests/index.blade.php', $leaveRequests);

// 3. timesheets/index.blade.php
$timesheets = <<<'BLADE'
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
    <div class="filter-card">
        <form method="get" action="{{ route('timesheets.index') }}" class="filter-toolbar">
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
                    <a href="{{ route('timesheets.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
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
                        <tr class="hover:bg-slate-50/60 transition-colors">
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
                                    <a href="{{ route('timesheets.edit-page', $row) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Timesheet">
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
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/timesheets/index.blade.php', $timesheets);

// 4. vendor-bills/index.blade.php
$vendorBills = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'vendor-bills', 'pageTitle' => 'Tagihan Vendor (AP)'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Tagihan Pemasok / Vendor Bills (AP)"
        subtitle="Kelola kewajiban utang dagang, verifikasi kesesuaian pesanan barang/jasa, dan riwayat pembayaran vendor."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Bills']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="vendor-bills" :create-route="route('vendor-bills.create-page')" create-label="Tambah Tagihan Vendor" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('vendor-bills.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari nomor tagihan, vendor..." value="{{ request('search') }}" class="filter-search">
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="unpaid" @selected(request('status') == 'unpaid')>Belum Dibayar (Unpaid)</option>
                <option value="partial" @selected(request('status') == 'partial')>Sebagian (Partial)</option>
                <option value="paid" @selected(request('status') == 'paid')>Lunas (Paid)</option>
                <option value="void" @selected(request('status') == 'void')>Batal (Void)</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('vendor-bills.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Nomor Tagihan</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Tgl Tagihan</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4 text-right">Total Tagihan</th>
                        <th class="py-3 px-4 text-right">Sisa Hutang</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($vendorBills as $bill)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-[#002875]">{{ $bill->bill_number }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $bill->vendor->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ $bill->currency_code ?? 'IDR' }} {{ number_format($bill->total_amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-bold {{ $bill->remaining_balance > 0 ? 'text-red-600' : 'text-slate-500' }}">
                                {{ $bill->currency_code ?? 'IDR' }} {{ number_format($bill->remaining_balance, 2, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$bill->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('vendor-bills.edit-page', $bill) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-bills.destroy', $bill) }}', 'Tagihan {{ $bill->bill_number }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Tagihan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-erp.empty-state message="Belum ada data tagihan vendor." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$vendorBills" />
</div>

<x-erp.confirm-dialog />
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/vendor-bills/index.blade.php', $vendorBills);

// 5. vendor-payments/index.blade.php
$vendorPayments = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'vendor-payments', 'pageTitle' => 'Pembayaran Vendor (AP)'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <x-erp.page-header
        title="Pembayaran Tagihan Vendor (AP Payments)"
        subtitle="Pencatatan realisasi pembayaran utang dagang ke rekening bank/kas vendor serta nomor referensi transfer."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Finance & Accounting', 'url' => route('finance-suite.index')], ['label' => 'Vendor Payments']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="vendor-payments" :create-route="route('vendor-payments.create-page')" create-label="Catat Pembayaran Vendor" />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('vendor-payments.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari referensi, nomor tagihan..." value="{{ request('search') }}" class="filter-search">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <a href="{{ route('vendor-payments.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal Bayar</th>
                        <th class="py-3 px-4">Tagihan Vendor (Bill #)</th>
                        <th class="py-3 px-4">Rekening Bank / Kas Pengirim</th>
                        <th class="py-3 px-4 text-right">Nominal Keluar</th>
                        <th class="py-3 px-4">Referensi / No Resi</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($vendorPayments as $payment)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-[#002875]">{{ $payment->vendorBill->bill_number ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $payment->bankAccount->name ?? 'Kas / Bank Utama' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900">{{ $payment->currency_code ?? 'IDR' }} {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $payment->reference ?? '-' }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('vendor-payments.destroy', $payment) }}', 'Pembayaran {{ $payment->vendorBill->bill_number ?? '' }} ({{ $payment->payment_date }})')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Pembayaran">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-erp.empty-state message="Belum ada data pembayaran vendor." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$vendorPayments" />
</div>

<x-erp.confirm-dialog />
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/vendor-payments/index.blade.php', $vendorPayments);

echo "Standardized attendances, leave-requests, timesheets, vendor-bills, vendor-payments to standalone 4-card structure!\n";
