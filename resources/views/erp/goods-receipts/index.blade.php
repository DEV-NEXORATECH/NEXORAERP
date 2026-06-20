@extends('layouts.erp', ['activePage' => 'goods-receipts', 'pageTitle' => 'Receipt Verification'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="goods-receipts">
    <div class="section-head">
        <h2>Receipt Verification</h2>
        <a href="{{ route('goods-receipts.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Catat Receipt
        </a>
    </div>

    <form method="get" action="{{ route('goods-receipts.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="received" @selected(request('status') == 'received')>Received</option>
            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
            <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('goods-receipts.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

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
        <span>Menampilkan {{ $receipts->firstItem() }}-{{ $receipts->lastItem() }} dari {{ $receipts->total() }}</span>
        {{ $receipts->links() }}
    </div>
    @endif
</section>
@endsection
