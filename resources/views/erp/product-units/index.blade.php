@extends('layouts.erp', ['activePage' => 'product-units', 'pageTitle' => 'Units of Measure'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="product-units">
    <div class="section-head">
        <h2>Satuan Produk</h2>
        <a href="{{ route('product-units.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('product-units.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('product-units.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Jumlah Produk</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($units as $unit)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $unit->code }}</td>
                <td class="font-bold">{{ $unit->name }}</td>
                <td>{{ ucfirst($unit->category) }}</td>
                <td>{{ $unit->products_count }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $unit->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('product-units.edit-page', $unit) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('product-units.destroy', $unit) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada satuan produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($units->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $units->firstItem() }}-{{ $units->lastItem() }} dari {{ $units->total() }}</span>
        {{ $units->links() }}
    </div>
    @endif
</section>
@endsection
