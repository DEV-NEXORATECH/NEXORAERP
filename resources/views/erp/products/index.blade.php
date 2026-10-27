@extends('layouts.erp', ['activePage' => 'products', 'pageTitle' => 'Products'])

@section('content')
<div class="space-y-4">
    <!-- Card 1 — Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#002875]">Inventory</span>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#334155]">Produk</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#0F172A] tracking-tight">Produk & Layanan</h1>
            <p class="mt-0.5 text-xs text-[#475569]">Kelola katalog produk, layanan, SKU, unit harga, dan pemantauan stok persediaan.</p>
        </div>
        <x-erp.toolbar-actions
            module="products"
            :create-route="route('products.create-page')"
            create-label="+ Tambah Produk"
        />
    </div>

    <!-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) -->
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('products.index') }}" class="filter-toolbar">
            <input type="text" name="search" placeholder="Cari nama/kode/sku..." value="{{ request('search') }}" class="filter-search !w-64 text-xs">
            <button type="submit" class="inline-flex h-[34px] items-center px-3.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition flex-shrink-0 cursor-pointer">Filter</button>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="inline-flex h-[34px] items-center px-3 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition flex-shrink-0">Reset</a>
            @endif
        </form>
    </div>

    <!-- Card 3 — Table Card -->
    <div class="workspace-card bg-white rounded-lg border border-[#D1D5DB] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Kode</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Nama</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Kategori</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Satuan</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Stok</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Harga Jual</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Status</th><th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($products as $product)
            <tr>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $product->code }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $product->name }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $product->category?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $product->unit?->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">
                    @php $stock = $product->stockOnHand(); @endphp
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $stock <= 0 ? 'bg-red-100 text-red-700' : ($product->isLowStock() ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                        {{ rtrim(rtrim(number_format($stock, 4, '.', ''), '0'), '.') }} {{ $product->unit?->code ?? '' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $rp($product->price) }}</td>
                <td class="px-3 py-2 text-xs text-[#334155] text-left">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                    <div class="inline-flex items-center gap-1.5">
                        <a href="{{ route('products.show', $product) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Lihat Detail">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('products.edit-page', $product) }}" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                        <form method="post" action="{{ route('products.destroy', $product) }}" class="inline" class="inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Hapus produk ini?">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-8 text-center text-slate-400">Belum ada produk.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 4 — Pagination (Card Terpisah) -->
    <x-erp.pagination :paginator="$products" />
</div>

<x-erp.import-modal module="products" title="Impor Data Produk" />
@endsection

