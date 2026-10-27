@extends('layouts.erp', ['activePage' => 'reimbursements', 'pageTitle' => 'Klaim Reimbursement'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <x-erp.page-header
        title="Klaim Reimbursement Karyawan"
        subtitle="Manajemen dan verifikasi pengajuan klaim biaya dinas, approval klaim, serta pencatatan bukti pengeluaran."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HRIS', 'url' => route('hris.index')], ['label' => 'Reimbursements']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions
                module="reimbursements"
                :create-route="route('reimbursements.create-page')"
                create-label="Ajukan Reimbursement"
                :can-create="$can('admin', 'hr', 'finance')"
            />
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
        <form method="get" action="{{ route('reimbursements.index-page') }}" class="filter-toolbar">
            <select name="status" class="text-xs">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status')==='pending')>Pending</option>
                <option value="approved" @selected(request('status')==='approved')>Approved</option>
                <option value="paid" @selected(request('status')==='paid')>Paid</option>
                <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
            </select>
            <select name="employee_id" class="text-xs">
                <option value="">Semua Karyawan</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" @selected(request('employee_id')==$employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs" title="Dari Tanggal">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs" title="Sampai Tanggal">
            <button type="submit" class="inline-flex h-[34px] items-center px-3.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition flex-shrink-0 cursor-pointer">Filter</button>
            @if(request()->hasAny(['status', 'employee_id', 'date_from', 'date_to']))
                <a href="{{ route('reimbursements.index-page') }}" class="inline-flex h-[34px] items-center px-3 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition flex-shrink-0">Reset</a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Main Table Card --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-[#D1D5DB] bg-slate-50/75 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Proyek Terkait</th>
                        <th class="py-3 px-4">Kategori & Keterangan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Nominal Klaim</th>
                        <th class="py-3 px-4 text-center">Bukti Nota</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($reimbursementsPage as $reimbursement)
                        <tr class="hover:bg-[#F3F4F6]/60 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $reimbursement->employee->name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $reimbursement->expense_date ? \Carbon\Carbon::parse($reimbursement->expense_date)->format('d/m/Y') : '-' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($reimbursement->project)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-[#002875]">
                                        {{ $reimbursement->project->code }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Non Project</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-700">{{ $reimbursement->category }}</div>
                                <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $reimbursement->description ?: '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <x-erp.status-badge :status="$reimbursement->status" />
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-900 whitespace-nowrap">
                                {{ $rp($reimbursement->amount) }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($reimbursement->receipt_file_path)
                                    <a href="{{ asset('storage/' . $reimbursement->receipt_file_path) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#002875] hover:underline">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        Nota
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('reimbursements.show', $reimbursement) }}"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail Klaim">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('reimbursements.pdf', $reimbursement) }}" target="_blank"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Cetak PDF">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    @if($can('admin', 'hr', 'finance'))
                                    <a href="{{ route('reimbursements.edit-page', $reimbursement) }}"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit Klaim">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endif
                                    @if($can('admin'))
                                    <button type="button"
                                        onclick="window.showDeleteModal('{{ route('reimbursements.destroy', $reimbursement) }}', 'Klaim {{ $reimbursement->employee->name ?? 'Karyawan' }} ({{ $reimbursement->category }})')"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]"
                                        title="Hapus Klaim">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-erp.empty-state message="Belum ada riwayat pengajuan reimbursement." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$reimbursementsPage" />
</div>

<x-erp.import-modal module="reimbursements" title="Impor Data Reimbursement" />
<x-erp.confirm-dialog />
@endsection

