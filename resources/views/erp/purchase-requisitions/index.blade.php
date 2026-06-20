@extends('layouts.erp', ['activePage' => 'purchase-requisitions', 'pageTitle' => 'Purchase Requisition'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="purchase-requisitions">
    <div class="section-head">
        <h2>Purchase Requisition</h2>
        <a href="{{ route('purchase-requisitions.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah PR
        </a>
    </div>

    <form method="get" action="{{ route('purchase-requisitions.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari PR..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
            <option value="submitted" @selected(request('status') == 'submitted')>Submitted</option>
            <option value="approved" @selected(request('status') == 'approved')>Approved</option>
            <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('purchase-requisitions.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>PR</th><th>Pemohon</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($requisitions as $row)
                <tr>
                    <td class="font-bold">{{ $row->number }}<br><span class="muted">{{ $row->title }}</span></td>
                    <td>{{ $row->requester->name ?? '-' }}<br><span class="muted">{{ $row->department->name ?? '-' }}</span></td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'approved' ? 'active' : ($row->status === 'rejected' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('purchase-requisitions.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('purchase-requisitions.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada purchase requisition.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($requisitions->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $requisitions->firstItem() }}-{{ $requisitions->lastItem() }} dari {{ $requisitions->total() }}</span>
        {{ $requisitions->links() }}
    </div>
    @endif
</section>
@endsection
