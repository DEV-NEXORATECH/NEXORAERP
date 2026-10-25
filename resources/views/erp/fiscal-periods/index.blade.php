@extends('layouts.erp', ['activePage' => 'fiscal-periods', 'pageTitle' => 'Fiscal Periods'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Periode Fiskal (Fiscal Periods)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Periode Fiskal (Fiscal Periods)</p>
        </div>
        <x-erp.toolbar-actions module="fiscal-periods" :create-route="route('fiscal-periods.create-page')" create-label="+ Tambah Periode" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('fiscal-periods.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
            <option value="">Semua Status</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('fiscal-periods.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kode</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Mulai</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Selesai</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($periods as $period)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $period->code }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $period->name }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $period->start_date->format('d M Y') }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $period->end_date->format('d M Y') }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $period->status === 'open' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ ucfirst($period->status) }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <a href="{{ route('fiscal-periods.edit-page', $period) }}" class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition mr-1">Edit</a>
                    <form method="post" action="{{ route('fiscal-periods.toggle', $period) }}" class="inline">
                        @csrf
                        <button class="mini">{{ $period->status === 'open' ? 'Close' : 'Open' }}</button>
                    </form>
                    <form method="post" action="{{ route('fiscal-periods.destroy', $period) }}" class="inline">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada periode fiskal.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($periods->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$periods->firstItem()}}-{{$periods->lastItem()}} dari {{$periods->total()}}</span>
            {{$periods->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="fiscal-periods" title="Impor Data Fiscal Periods" />
@endsection