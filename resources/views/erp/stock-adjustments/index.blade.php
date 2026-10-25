@extends('layouts.erp', ['activePage' => 'stock-adjustments', 'pageTitle' => 'Stock Adjustments'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Penyesuaian Stok (Stock Opname)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Penyesuaian Stok (Stock Opname)</p>
        </div>
        <a href="{{ route('stock-adjustments.create-page') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Penyesuaian Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('stock-adjustments.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari nomor/alasan..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('stock-adjustments.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                        <form method="post" action="{{ route('stock-adjustments.destroy', $adjustment) }}" class="inline">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Delete</button></form>
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
        @if($adjustments->hasPages())
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{$adjustments->firstItem()}}-{{$adjustments->lastItem()}} dari {{$adjustments->total()}}</span>
            {{$adjustments->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
