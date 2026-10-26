@extends('layouts.erp', ['activePage' => 'stock-adjustments', 'pageTitle' => 'Stock Adjustments'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Penyesuaian Stok (Stock Opname)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Penyesuaian Stok (Stock Opname)</p>
        </div>
        <x-erp.toolbar-actions module="stock-adjustments" :create-route="route('stock-adjustments.create-page')" create-label="+ Penyesuaian Baru" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('stock-adjustments.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search pl-8 pr-3 text-xs">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('stock-adjustments.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">No. Adjustment</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Produk</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Expected</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Actual</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Selisih</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($adjustments as $adjustment)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $adjustment->number }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $adjustment->adjustment_date }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $adjustment->product?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ rtrim(rtrim(number_format($adjustment->expected_quantity, 4, '.', ''), '0'), '.') }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ rtrim(rtrim(number_format($adjustment->actual_quantity, 4, '.', ''), '0'), '.') }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    {{ $adjustment->deltaQuantity() > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($adjustment->deltaQuantity(), 4, '.', ''), '0'), '.') }}
                </td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $adjustment->status === 'posted' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ ucfirst($adjustment->status) }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    @if($adjustment->status !== 'posted')
                        <form method="post" action="{{ route('stock-adjustments.post', $adjustment) }}" class="inline">@csrf<button class="mini">Post</button></form>
                        <form method="post" action="{{ route('stock-adjustments.destroy', $adjustment) }}" class="inline" onsubmit="return confirm('Hapus penyesuaian stok ini?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                    @else
                        <span class="text-xs muted">Posted</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-8 text-center text-slate-400">Belum ada adjustment stok.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($adjustments->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$adjustments->firstItem()}}-{{$adjustments->lastItem()}} dari {{$adjustments->total()}}</span>
            {{$adjustments->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="stock-adjustments" title="Impor Data Stock Adjustments" />
@endsection