@extends('layouts.erp', ['activePage' => 'product-units', 'pageTitle' => 'Tambah Satuan Produk'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="product-unit-create">
    <div class="section-head">
        <h2>Tambah Satuan Produk</h2>
    </div>

    <form method="post" action="{{ route('product-units.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="PCS" required value="{{ old('code') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kategori
                <select name="category" required>
                    <option value="unit" @selected(old('category', 'unit') === 'unit')>Unit</option>
                    <option value="weight" @selected(old('category') === 'weight')>Weight</option>
                    <option value="volume" @selected(old('category') === 'volume')>Volume</option>
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Pieces" required value="{{ old('name') }}">
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Tambah Satuan</button>
            <a href="{{ route('product-units.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
