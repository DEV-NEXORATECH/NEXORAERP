@extends('layouts.erp', ['activePage' => 'stock-movements', 'pageTitle' => 'Stock Movements'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="stock-movements">
    <div class="section-head">
        <h2>Movement Stok</h2>
        <a href="{{ route('stock-movements.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Catat Movement
        </a>
    </div>

    <form method="get" action="{{ route('stock-movements.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari referensi/notes..." value="{{ request('search') }}" class="field-input">
        <select name="type">
            <option value="">Semua Tipe</option>
            @foreach(['in', 'out', 'adjustment'] as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('stock-movements.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Referensi</th><th>Tanggal</th><th>Produk</th><th>Tipe</th><th>Qty</th><th>Gudang</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($movements as $movement)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $movement->reference ?? '-' }}</td>
                <td class="whitespace-nowrap">{{ $movement->movement_date }}</td>
                <td class="font-bold">{{ $movement->product?->name ?? '-' }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $movement->type === 'in' ? 'bg-emerald-100 text-emerald-700' : ($movement->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td class="{{ $movement->type === 'out' ? 'text-red-600' : ($movement->type === 'in' ? 'text-emerald-600' : '') }}">{{ $movement->type === 'out' ? '-' : '' }}{{ rtrim(rtrim(number_format($movement->quantity, 4, '.', ''), '0'), '.') }}</td>
                <td>{{ $movement->warehouse?->name ?? '-' }}</td>
                <td class="actions">
                    <form method="post" action="{{ route('stock-movements.destroy', $movement) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-8 text-center text-slate-400">Belum ada movement stok.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($movements->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $movements->firstItem() }}-{{ $movements->lastItem() }} dari {{ $movements->total() }}</span>
        {{ $movements->links() }}
    </div>
    @endif
</section>
@endsection
