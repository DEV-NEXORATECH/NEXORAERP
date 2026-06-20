@extends('layouts.erp', ['activePage' => 'fixed-assets.index', 'pageTitle' => 'Fixed Assets'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="fixed-assets">
    <div class="section-head">
        <h2>Fixed Assets</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('fixed-assets.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('fixed-assets.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="text" name="category" placeholder="Kategori..." value="{{ request('category') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="disposed" @selected(request('status') == 'disposed')>Disposed</option>
            <option value="sold" @selected(request('status') == 'sold')>Sold</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('fixed-assets.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Nama</th><th>Kode</th><th>Kategori</th><th>Harga Perolehan</th><th>Nilai Buku</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($assets as $row)
                <tr>
                    <td class="font-bold">{{ $row->name }}<br><span class="muted">{{ $row->asset_code ?? '-' }}</span></td>
                    <td>{{ $row->category ?? '-' }}</td>
                    <td>{{ $rp($row->purchase_cost) }}</td>
                    <td>{{ $rp($row->book_value ?? $row->purchase_cost - $row->accumulated_depreciation) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('fixed-assets.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('fixed-assets.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada fixed asset.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($assets->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $assets->firstItem() }}-{{ $assets->lastItem() }} dari {{ $assets->total() }}</span>
        {{ $assets->links() }}
    </div>
    @endif
</section>
@endsection
