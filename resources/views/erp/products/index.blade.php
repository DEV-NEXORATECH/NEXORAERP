@extends('layouts.erp', ['activePage' => 'products', 'pageTitle' => 'Products'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Produk & Layanan</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Produk & Layanan</p>
        </div>
        <a href="{{ route('products.create-page') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('products.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari nama/kode/sku..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('products.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kode</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kategori</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Satuan</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Stok</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Harga Jual</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($products as $product)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $product->code }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $product->name }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $product->category?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $product->unit?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    @php $stock = $product->stockOnHand(); @endphp
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $stock <= 0 ? 'bg-red-100 text-red-700' : ($product->isLowStock() ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                        {{ rtrim(rtrim(number_format($stock, 4, '.', ''), '0'), '.') }} {{ $product->unit?->code ?? '' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($product->price) }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <a href="{{ route('products.edit-page', $product) }}" class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition mr-1">Edit</a>
                    <form method="post" action="{{ route('products.destroy', $product) }}" class="inline">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-8 text-center text-slate-400">Belum ada produk.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{$products->firstItem()}}-{{$products->lastItem()}} dari {{$products->total()}}</span>
            {{$products->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
