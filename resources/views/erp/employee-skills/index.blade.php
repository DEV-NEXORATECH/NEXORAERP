@extends('layouts.erp', ['activePage' => 'employee-skills', 'pageTitle' => 'Employee Skills'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Daftar Kompetensi Karyawan"
        subtitle="Pemetaan keahlian, bahasa pemrograman, dan spesialisasi fungsional seluruh staf."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'HR & Karyawan', 'url' => route('hris.index')], ['label' => 'Skills']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="employee-skills" :create-route="route('employee-skills.create-page')" create-label="+ Tambah Skill" />
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
        <form method="get" action="{{ route('employee-skills.index') }}" class="filter-toolbar">
            
            <div>
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
            </div>
            <div>
                <select name="employee_id" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="level" class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Level</option>
                    <option value="basic" @selected(request('level') === 'basic')>Basic</option>
                    <option value="intermediate" @selected(request('level') === 'intermediate')>Intermediate</option>
                    <option value="advanced" @selected(request('level') === 'advanced')>Advanced</option>
                    <option value="expert" @selected(request('level') === 'expert')>Expert</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                <a href="{{ route('employee-skills.index') }}" class="inline-flex items-center rounded-md border border-[#D1D5DB] bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    Reset
                </a>
            </div>
        
        </form>
    </div>

    <div class="rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Skill / Keahlian</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Tingkat Level</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Catatan</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $row)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                {{ $row->employee->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-800 font-medium">
                                {{ $row->skill }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $levelBadge = match(strtolower($row->level)) {
                                        'expert' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'advanced' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'intermediate' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-slate-50 text-slate-700 border-[#D1D5DB]'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $levelBadge }}">
                                    {{ ucfirst($row->level) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $row->notes ?: '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin', 'hr'))
                                        <a href="{{ route('employee-skills.edit-page', $row) }}" class="inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] hover:text-[#002875] transition-colors" title="Edit">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('employee-skills.destroy', $row) }}" class="inline" class="inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Apakah Anda yakin ingin menghapus skill ini?">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data kompetensi skill yang terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($items->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }} kompetensi</span>
                {{ $items->links() }}
        </div>
    @endif
</div>

<x-erp.import-modal module="employee-skills" title="Impor Data Employee Skills" />
@endsection