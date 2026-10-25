@extends('layouts.erp', ['activePage' => 'stock-adjustments', 'pageTitle' => 'Stock Adjustments'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="stock-adjustments">
    <div class="section-head">
        <h2>Adjustment Stok</h2>
        <a href="{{ route('stock-adjustments.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Buat Adjustment
        </a>
    </div>

    <form method="get" action="{{ route('stock-adjustments.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari nomor/alasan..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('stock-adjustments.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>No. Adjustment</th><th>Tanggal</th><th>Produk</th><th>Expected</th><th>Actual</th><th>Selisih</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($adjustments as $adjustment)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $adjustment->number }}</td>
                <td class="whitespace-nowrap">{{ $adjustment->adjustment_date }}</td>
                <td class="font-bold">{{ $adjustment->product?->name ?? '-' }}</td>
                <td>{{ rtrim(rtrim(number_format($adjustment->expected_quantity, 4, '.', ''), '0'), '.') }}</td>
                <td>{{ rtrim(rtrim(number_format($adjustment->actual_quantity, 4, '.', ''), '0'), '.') }}</td>
                <td class="{{ $adjustment->deltaQuantity() > 0 ? 'text-emerald-600' : ($adjustment->deltaQuantity() < 0 ? 'text-red-600' : '') }}">
                    {{ $adjustment->deltaQuantity() > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($adjustment->deltaQuantity(), 4, '.', ''), '0'), '.') }}
                </td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $adjustment->status === 'posted' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ ucfirst($adjustment->status) }}
                    </span>
                </td>
                <td class="actions">
                    @if($adjustment->status !== 'posted')
                        <form method="post" action="{{ route('stock-adjustments.post', $adjustment) }}" class="inline">@csrf<button class="mini">Post</button></form>
                        <form method="post" action="{{ route('stock-adjustments.destroy', $adjustment) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
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

    @if($adjustments->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $adjustments->firstItem() }}-{{ $adjustments->lastItem() }} dari {{ $adjustments->total() }}</span>
        {{ $adjustments->links() }}
    </div>
    @endif
</section>
@endsection
