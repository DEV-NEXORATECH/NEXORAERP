@extends('layouts.erp', ['activePage' => 'stock-movements', 'pageTitle' => 'Stock Movements'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Perpindahan Stok (Movement)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Perpindahan Stok (Movement)</p>
        </div>
        <x-erp.toolbar-actions module="stock-movements" :create-route="route('stock-movements.create-page')" create-label="+ Catat Perpindahan" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('stock-movements.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search !w-full pl-8 pr-3 text-xs">
        <select name="type">
            <option value="">Semua Tipe</option>
            @foreach(['in', 'out', 'adjustment'] as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('stock-movements.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Referensi</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Produk</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tipe</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Qty</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Gudang</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($movements as $movement)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $movement->reference ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $movement->movement_date }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $movement->product?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $movement->type === 'in' ? 'bg-emerald-100 text-emerald-700' : ($movement->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $movement->type === 'out' ? '-' : '' }}{{ rtrim(rtrim(number_format($movement->quantity, 4, '.', ''), '0'), '.') }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $movement->warehouse?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <form method="post" action="{{ route('stock-movements.destroy', $movement) }}" class="inline">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-8 text-center text-slate-400">Belum ada movement stok.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($movements->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$movements->firstItem()}}-{{$movements->lastItem()}} dari {{$movements->total()}}</span>
            {{$movements->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="stock-movements" title="Impor Data Stock Movements" />
@endsection