@extends('layouts.erp', ['activePage' => 'product-categories', 'pageTitle' => 'Product Categories'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Kategori Produk</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Kategori Produk</p>
        </div>
        <x-erp.toolbar-actions module="product-categories" :create-route="route('product-categories.create-page')" create-label="+ Tambah Kategori" />
    </div>

    <!-- Table Card -->
    
    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('product-categories.index') }}" class="filter-toolbar">
            
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="filter-search !w-full pl-8 pr-3 text-xs">
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('product-categories.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kode</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Parent</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Jumlah Produk</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($categories as $category)
            <tr>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $category->code }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $category->name }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $category->parent?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $category->products_count }}</td>
                <td class="px-3 py-2 text-xs text-gray-700 text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <a href="{{ route('product-categories.edit-page', $category) }}" class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition mr-1">Edit</a>
                    <form method="post" action="{{ route('product-categories.destroy', $category) }}" class="inline">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada kategori produk.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
        <div class="pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <span>Menampilkan {{$categories->firstItem()}}-{{$categories->lastItem()}} dari {{$categories->total()}}</span>
            {{$categories->links()}}
        </div>
    @endif
</div>

<x-erp.import-modal module="product-categories" title="Impor Data Product Categories" />
@endsection