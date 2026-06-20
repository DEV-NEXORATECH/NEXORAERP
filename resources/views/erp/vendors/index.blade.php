@extends('layouts.erp', ['activePage' => 'vendors', 'pageTitle' => 'Vendor / Supplier Management'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Vendor / Supplier Management</h2>
        <a class="button ghost" href="{{ route('vendors.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Vendor
        </a>
    </div>
    <table>
        <thead>
            <tr><th>Vendor</th><th>Kontak</th><th>Terms</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($vendors as $row)
                <tr>
                    <td class="font-bold">{{ $row->name }}<br><span class="muted">{{ $row->category }}</span></td>
                    <td>{{ $row->contact_name ?? '-' }}<br><span class="muted">{{ $row->email ?? $row->phone ?? '' }}</span></td>
                    <td>{{ $row->payment_terms ?? '-' }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'void' }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('vendors.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('vendors.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada vendor.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($vendors->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $vendors->firstItem() }}–{{ $vendors->lastItem() }} dari {{ $vendors->total() }}</div>
            {{ $vendors->links() }}
        </div>
    @endif
</section>
@endsection
