@extends('layouts.erp', ['activePage' => 'goods-receipts', 'pageTitle' => 'Receipt Verification'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Receipt Verification</h2>
        <a class="button ghost" href="{{ route('goods-receipts.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Catat Receipt
        </a>
    </div>
    <table>
        <thead>
            <tr><th>PO</th><th>Tanggal</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($receipts as $row)
                <tr>
                    <td>{{ $row->purchaseOrder->number ?? '-' }}</td>
                    <td>{{ $row->receipt_date?->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'rejected' ? 'void' : 'active' }}">{{ $row->status }}</span></td>
                    <td class="muted">{{ Str::limit($row->notes, 40) ?? '-' }}</td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('goods-receipts.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('goods-receipts.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada receipt.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($receipts->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $receipts->firstItem() }}–{{ $receipts->lastItem() }} dari {{ $receipts->total() }}</div>
            {{ $receipts->links() }}
        </div>
    @endif
</section>
@endsection
