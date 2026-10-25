@extends('layouts.erp', ['activePage' => 'purchase-matches.index', 'pageTitle' => 'Purchase Matches'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="purchase-matches">
    <div class="section-head">
        <h2>Purchase Matches</h2>
        @if($can('admin', 'finance'))
        <a href="{{ route('purchase-matches.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('purchase-matches.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="matched" @selected(request('status') == 'matched')>Matched</option>
            <option value="unmatched" @selected(request('status') == 'unmatched')>Unmatched</option>
            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('purchase-matches.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

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
        <span>Menampilkan {{ $matches->firstItem() }}-{{ $matches->lastItem() }} dari {{ $matches->total() }}</span>
        {{ $matches->links() }}
    </div>
    @endif
</section>
@endsection
