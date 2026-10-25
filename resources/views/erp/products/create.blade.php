@extends('layouts.erp', ['activePage' => 'product-create', 'pageTitle' => 'Tambah Produk'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Tambah Produk</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('products.store') }}" class="grid max-w-2xl gap-4" class="space-y-4">
            
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="PRD-001" required value="{{ old('code') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tipe
                <select name="type" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="product" @selected(old('type', 'product') === 'product')>Product</option>
                    <option value="service" @selected(old('type') === 'service')>Service</option>
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Nama produk/jasa" required value="{{ old('name') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kategori
                <select name="category_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="">-</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Satuan
                <select name="unit_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
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
                <input name="sku" placeholder="SKU-001" value="{{ old('sku') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Barcode
                <input name="barcode" placeholder="8990000000000" value="{{ old('barcode') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Pokok (Cost)
                <input type="number" name="cost" step="0.01" min="0" required value="{{ old('cost', 0) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Jual
                <input type="number" name="price" step="0.01" min="0" required value="{{ old('price', 0) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Min. Stok
                <input type="number" name="min_stock" step="0.0001" min="0" value="{{ old('min_stock', 0) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Deskripsi
            <textarea name="description" rows="3" placeholder="Deskripsi produk..." class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">{{ old('description') }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        
    
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('products.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Tambah Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
