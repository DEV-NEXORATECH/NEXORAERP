@extends('layouts.erp', ['activePage' => 'stock-movements', 'pageTitle' => 'Catat Movement Stok'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Catat Perpindahan</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('stock-movements.store') }}" class="space-y-4">
            
        @csrf
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Produk
            <select name="product_id" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected((int) old('product_id') === $product->id)>{{ $product->code }} - {{ $product->name }}</option>
                @endforeach
            </select>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tipe
                <select name="type" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    @foreach(['in', 'out', 'adjustment'] as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal
                <input type="date" name="movement_date" required value="{{ old('movement_date', now()->toDateString()) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Jumlah (qty)
                <input type="number" name="quantity" step="0.0001" min="0.0001" required value="{{ old('quantity') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Harga Satuan (opsional)
                <input type="number" name="unit_price" step="0.01" min="0" value="{{ old('unit_price', 0) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Gudang
                <select name="warehouse_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="">-</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected((int) old('warehouse_id') === $warehouse->id)>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Lokasi
                <select name="location_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="">-</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" @selected((int) old('location_id') === $location->id)>{{ $location->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Notes
            <input name="notes" placeholder="Catatan movement..." value="{{ old('notes') }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
        </label>
        
    
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('stock-movements.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Catat Movement</button>
            </div>
        </form>
    </div>
</div>
@endsection
