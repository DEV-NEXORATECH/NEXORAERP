@extends('layouts.erp', ['activePage' => 'products', 'pageTitle' => 'Products'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="products">
    <div class="section-head">
        <h2>Produk</h2>
        <a href="{{ route('products.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Produk
        </a>
    </div>

    <form method="get" action="{{ route('products.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari nama/kode/sku..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('products.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Harga Jual</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $product->code }}</td>
                <td class="font-bold">{{ $product->name }}</td>
                <td>{{ $product->category?->name ?? '-' }}</td>
                <td>{{ $product->unit?->name ?? '-' }}</td>
                <td>
                    @php $stock = $product->stockOnHand(); @endphp
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $stock <= 0 ? 'bg-red-100 text-red-700' : ($product->isLowStock() ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                        {{ rtrim(rtrim(number_format($stock, 4, '.', ''), '0'), '.') }} {{ $product->unit?->code ?? '' }}
                    </span>
                </td>
                <td>{{ $rp($product->price) }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('products.edit-page', $product) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('products.destroy', $product) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-8 text-center text-slate-400">Belum ada produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }}</span>
        {{ $products->links() }}
    </div>
    @endif
</section>
@endsection
