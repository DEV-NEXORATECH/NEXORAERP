@extends('layouts.erp', ['activePage' => 'purchase-matches.index', 'pageTitle' => 'Purchase Matches'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Purchase Matches</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('purchase-matches.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <table>
        <thead>
            <tr><th>PO</th><th>Goods Receipt</th><th>Variance</th><th>Status</th><th>Matched At</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($matches as $row)
                <tr>
                    <td class="font-bold">{{ $row->purchaseOrder?->number ?? '#' . $row->purchase_order_id ?? '-' }}</td>
                    <td>#{{ $row->goodsReceipt?->id ?? $row->goods_receipt_id ?? '-' }}</td>
                    <td>{{ $row->variance_amount !== null ? $rp($row->variance_amount) : '-' }}</td>
                    <td><span class="badge badge-{{ $row->match_status === 'matched' ? 'active' : ($row->match_status === 'partial' ? 'pending' : 'void') }}">{{ $row->match_status }}</span></td>
                    <td>{{ $row->matched_at ?? '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('purchase-matches.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'finance'))<form method="post" action="{{ route('purchase-matches.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada purchase match.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($matches->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">Menampilkan {{ $matches->firstItem() }}–{{ $matches->lastItem() }} dari {{ $matches->total() }}</div>
            {{ $matches->links() }}
        </div>
    @endif
</section>
@endsection
