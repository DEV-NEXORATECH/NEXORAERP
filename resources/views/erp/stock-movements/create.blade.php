@extends('layouts.erp', ['activePage' => 'stock-movements', 'pageTitle' => 'Catat Movement Stok'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="stock-movement-create">
    <div class="section-head">
        <h2>Catat Movement Stok</h2>
    </div>

    <form method="post" action="{{ route('stock-movements.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Produk
            <select name="product_id" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected((int) old('product_id') === $product->id)>{{ $product->code }} - {{ $product->name }}</option>
                @endforeach
            </select>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tipe
                <select name="type" required>
                    @foreach(['in', 'out', 'adjustment'] as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal
                <input type="date" name="movement_date" required value="{{ old('movement_date', now()->toDateString()) }}">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Jumlah (qty)
                <input type="number" name="quantity" step="0.0001" min="0.0001" required value="{{ old('quantity') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Satuan (opsional)
                <input type="number" name="unit_price" step="0.01" min="0" value="{{ old('unit_price', 0) }}">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Gudang
                <select name="warehouse_id">
                    <option value="">-</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected((int) old('warehouse_id') === $warehouse->id)>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Lokasi
                <select name="location_id">
                    <option value="">-</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" @selected((int) old('location_id') === $location->id)>{{ $location->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Notes
            <input name="notes" placeholder="Catatan movement..." value="{{ old('notes') }}">
        </label>
        <div class="flex gap-3">
            <button>Catat Movement</button>
            <a href="{{ route('stock-movements.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
