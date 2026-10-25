@extends('layouts.erp', ['activePage' => 'product-categories', 'pageTitle' => 'Product Categories'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="product-categories">
    <div class="section-head">
        <h2>Kategori Produk</h2>
        <a href="{{ route('product-categories.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('product-categories.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('product-categories.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Parent</th><th>Jumlah Produk</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $category->code }}</td>
                <td class="font-bold">{{ $category->name }}</td>
                <td>{{ $category->parent?->name ?? '-' }}</td>
                <td>{{ $category->products_count }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('product-categories.edit-page', $category) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('product-categories.destroy', $category) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada kategori produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari {{ $categories->total() }}</span>
        {{ $categories->links() }}
    </div>
    @endif
</section>
@endsection
