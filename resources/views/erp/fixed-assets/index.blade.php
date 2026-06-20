@extends('layouts.erp', ['activePage' => 'fixed-assets.index', 'pageTitle' => 'Fixed Assets'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Fixed Assets</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('fixed-assets.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">Menampilkan {{ $assets->firstItem() }}–{{ $assets->lastItem() }} dari {{ $assets->total() }}</div>
            {{ $assets->links() }}
        </div>
    @endif
</section>
@endsection
