@extends('layouts.erp', ['activePage' => 'attendances', 'pageTitle' => 'Presensi & Kehadiran Karyawan'])

@section('content')
<div class="space-y-6">
    <x-erp.page-header
        title="Presensi & Kehadiran Karyawan"
        subtitle="Pantau jam masuk, jam keluar, mode kerja (WFO / WFH / Remote), serta status kehadiran harian."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Attendances']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="attendances" :create-route="route('attendances.create-page')" create-label="+ Catat Presensi" />
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <x-erp.toolbar :resetUrl="route('attendances.index')">
            <x-slot:filters>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tgl"
                    class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                <select name="employee_id" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
                <select name="mode" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Mode</option>
                    <option value="office" @selected(request('mode') === 'office')>Office (WFO)</option>
                    <option value="remote" @selected(request('mode') === 'remote')>Remote (WFH)</option>
                    <option value="hybrid" @selected(request('mode') === 'hybrid')>Hybrid</option>
                </select>
            </x-slot:filters>
        </x-erp.toolbar>

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

    @if($items->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <div>
                    Menampilkan <strong>{{ $items->firstItem() ?? 0 }}</strong> - <strong>{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> entri presensi
                </div>
                <div>
                    {{ $items->links() }}
                </div>
        </div>
    @endif
</div>

<x-erp.confirm-dialog />

<x-erp.import-modal module="attendances" title="Impor Data Attendances" />
@endsection