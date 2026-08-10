@extends('layouts.erp', ['activePage' => 'warehouses', 'pageTitle' => 'Warehouses'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="warehouses">
    <div class="section-head">
        <h2>Gudang</h2>
        <a href="{{ route('warehouses.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>

    <form method="get" action="{{ route('warehouses.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('warehouses.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Alamat</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($warehouses as $warehouse)
            <tr>
                <td class="font-mono text-xs font-bold">{{ $warehouse->code }}</td>
                <td class="font-bold">{{ $warehouse->name }}</td>
                <td>{{ $warehouse->address ?? '-' }}</td>
                <td>{{ $warehouse->locations_count }}</td>
                <td>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $warehouse->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('warehouses.edit-page', $warehouse) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('warehouses.destroy', $warehouse) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada gudang.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($warehouses->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $warehouses->firstItem() }}-{{ $warehouses->lastItem() }} dari {{ $warehouses->total() }}</span>
        {{ $warehouses->links() }}
    </div>
    @endif
</section>
@endsection
