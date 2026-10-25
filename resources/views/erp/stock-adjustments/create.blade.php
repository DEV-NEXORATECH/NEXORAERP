@extends('layouts.erp', ['activePage' => 'stock-adjustment-create', 'pageTitle' => 'Buat Adjustment Stok'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="stock-adjustment-create">
    <div class="section-head">
        <h2>Buat Adjustment Stok</h2>
    </div>

    <form method="post" action="{{ route('stock-adjustments.store') }}" class="grid max-w-2xl gap-4">
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
                Qty Aktual (hasil opname)
                <input type="number" name="actual_quantity" step="0.0001" min="0" required value="{{ old('actual_quantity') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal
                <input type="date" name="adjustment_date" required value="{{ old('adjustment_date', now()->toDateString()) }}">
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
            Alasan
            <input name="reason" placeholder="Contoh: opname fisik, barang rusak" required value="{{ old('reason') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Notes
            <input name="notes" placeholder="Catatan tambahan..." value="{{ old('notes') }}">
        </label>
        <p class="text-xs text-slate-500">Qty expected dihitung otomatis dari stok berjalan saat dibuat. Selisih akan menjadi movement stok saat diposting.</p>
        <div class="flex gap-3">
            <button>Buat Adjustment</button>
            <a href="{{ route('stock-adjustments.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
