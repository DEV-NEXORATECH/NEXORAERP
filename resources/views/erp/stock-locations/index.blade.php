@extends('layouts.erp', ['activePage' => 'stock-locations', 'pageTitle' => 'Stock Locations'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="stock-locations">
    <div class="section-head">
        <h2>Lokasi Stok</h2>
        <a href="{{ route('stock-locations.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('stock-locations.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('stock-locations.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Gudang</th><th>Tipe</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($locations as $location)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $location->code }}</td>
                <td class="font-bold">{{ $location->name }}</td>
                <td>{{ $location->warehouse?->name ?? '-' }}</td>
                <td>{{ ucfirst($location->type) }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $location->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('stock-locations.edit-page', $location) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('stock-locations.destroy', $location) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada lokasi stok.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($locations->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $locations->firstItem() }}-{{ $locations->lastItem() }} dari {{ $locations->total() }}</span>
        {{ $locations->links() }}
    </div>
    @endif
</section>
@endsection
