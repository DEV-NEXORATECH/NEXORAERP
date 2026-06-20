@extends('layouts.erp', ['activePage' => 'purchase-orders', 'pageTitle' => 'Purchase Order'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Purchase Order</h2>
        <a class="button ghost" href="{{ route('purchase-orders.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah PO
        </a>
    </div>
    <table>
        <thead>
            <tr><th>PO</th><th>Vendor</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($orders as $row)
                <tr>
                    <td class="font-bold">{{ $row->number }}<br><span class="muted">{{ $row->order_date?->format('d M Y') }}</span></td>
                    <td>{{ $row->vendor->name ?? '-' }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'cancelled' ? 'void' : ($row->status === 'ordered' ? 'pending' : 'active') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('purchase-orders.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('purchase-orders.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada purchase order.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}</div>
            {{ $orders->links() }}
        </div>
    @endif
</section>
@endsection
