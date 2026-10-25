@extends('layouts.erp', ['activePage' => 'product-create', 'pageTitle' => 'Tambah Produk'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="product-create">
    <div class="section-head">
        <h2>Tambah Produk</h2>
    </div>

    <form method="post" action="{{ route('products.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="PRD-001" required value="{{ old('code') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tipe
                <select name="type" required>
                    <option value="product" @selected(old('type', 'product') === 'product')>Product</option>
                    <option value="service" @selected(old('type') === 'service')>Service</option>
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Nama produk/jasa" required value="{{ old('name') }}">
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kategori
                <select name="category_id">
                    <option value="">-</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Satuan
                <select name="unit_id">
                    <option value="">-</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @selected((int) old('unit_id') === $unit->id)>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                SKU
                <input name="sku" placeholder="SKU-001" value="{{ old('sku') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Barcode
                <input name="barcode" placeholder="8990000000000" value="{{ old('barcode') }}">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Pokok (Cost)
                <input type="number" name="cost" step="0.01" min="0" required value="{{ old('cost', 0) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Jual
                <input type="number" name="price" step="0.01" min="0" required value="{{ old('price', 0) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Min. Stok
                <input type="number" name="min_stock" step="0.0001" min="0" value="{{ old('min_stock', 0) }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Deskripsi
            <textarea name="description" rows="3" placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Tambah Produk</button>
            <a href="{{ route('products.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
