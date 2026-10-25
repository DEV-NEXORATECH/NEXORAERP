@extends('layouts.erp', ['activePage' => 'leave-requests', 'pageTitle' => 'Pengajuan Cuti & Izin Karyawan'])

@section('content')
<div class="space-y-6">
    <x-erp.page-header
        title="Pengajuan Cuti & Izin Karyawan"
        subtitle="Manajemen permohonan cuti tahunan, izin sakit, cuti khusus, serta persetujuan status izin."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Leave Requests']]"
    >
        <x-slot:actions>
            @if($can('admin', 'hr'))
            <a href="{{ route('leave-requests.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#001B50] focus:outline-none focus:ring-2 focus:ring-[#002875] transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Cuti Baru
            </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <x-erp.toolbar :resetUrl="route('leave-requests.index')">
            <x-slot:filters>
                <select name="status" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
                <select name="type" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Tipe Cuti</option>
                    <option value="annual" @selected(request('type') === 'annual')>Cuti Tahunan (Annual)</option>
                    <option value="sick" @selected(request('type') === 'sick')>Sakit (Sick)</option>
                    <option value="unpaid" @selected(request('type') === 'unpaid')>Cuti Tanpa Gaji (Unpaid)</option>
                    <option value="special" @selected(request('type') === 'special')>Cuti Khusus (Special)</option>
                </select>
                <select name="employee_id" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-[#002875] focus:outline-none">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </x-slot:filters>
        </x-erp.toolbar>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-slate-200 bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4 text-center">Tipe Cuti</th>
                        <th class="py-3 px-4">Tanggal Mulai</th>
                        <th class="py-3 px-4">Tanggal Selesai</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($items as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $row->employee->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 capitalize">
                                    {{ $row->type ?? 'Annual' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $row->start_date ? \Carbon\Carbon::parse($row->start_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$row->status" />
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($can('admin', 'hr'))
                                    <a href="{{ route('leave-requests.edit-page', $row) }}"
                                        class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-[#0369A1] transition-colors" title="Edit Cuti">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('leave-requests.destroy', $row) }}', 'Permohonan Cuti {{ $row->employee->name ?? 'Karyawan' }}')"
                                        class="rounded p-1 text-slate-400 hover:bg-red-50 hover:text-[#DC2626] transition-colors"
                                        title="Hapus Cuti">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-erp.empty-state message="Belum ada permohonan cuti terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="border-t border-slate-200 bg-slate-50/50 px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan <strong>{{ $items->firstItem() ?? 0 }}</strong> - <strong>{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> permohonan cuti
                </div>
                <div>
                    {{ $items->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<x-erp.confirm-dialog />
@endsection
