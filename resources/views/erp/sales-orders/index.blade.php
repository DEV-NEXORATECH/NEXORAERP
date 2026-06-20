@extends('layouts.erp', ['activePage' => 'sales-orders.index', 'pageTitle' => 'Sales Orders'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Sales Orders</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-orders.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>Number</th><th>Title</th><th>Amount</th><th>Order Date</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($orders as $row)
                <tr>
                    <td class="font-bold">{{ $row->number }}</td>
                    <td>{{ $row->title }}</td>
                    <td class="font-bold">{{ $rp($row->amount) }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->order_date)->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'confirmed' ? 'active' : ($row->status === 'cancelled' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-orders.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-orders.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada sales order.</td></tr>
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
