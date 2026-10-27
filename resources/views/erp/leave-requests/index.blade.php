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
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('leave-requests.index') }}" class="filter-toolbar erp-filter-toolbar">
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
                    <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
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
                        <tr class="hover:bg-[#F3F4F6]/60 transition-colors">
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
                                    <a data-action="print" href="{{ route('leave-requests.pdf', $row) }}" target="_blank"
                                        class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#CBD5E1] bg-[#F8FAFC] text-[#475569] hover:bg-[#F1F5F9]" title="Cetak Formulir Cuti PDF">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    @if($can('admin', 'hr'))
                                    <a data-action="edit" href="{{ route('leave-requests.edit-page', $row) }}"
                                        class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#DDD6FE] bg-[#F5F3FF] text-[#7C3AED] hover:bg-[#EDE9FE]" title="Edit Pengajuan">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('leave-requests.destroy', $row) }}', 'Pengajuan Cuti {{ $row->employee->name ?? 'Karyawan' }}')"
                                        class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]"
                                        title="Hapus Pengajuan">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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