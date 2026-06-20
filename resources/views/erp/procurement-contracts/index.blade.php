@extends('layouts.erp', ['activePage' => 'procurement-contracts', 'pageTitle' => 'Procurement Contract'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="procurement-contracts">
    <div class="section-head">
        <h2>Procurement Contract</h2>
        <a href="{{ route('procurement-contracts.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Contract
        </a>
    </div>

    <form method="get" action="{{ route('procurement-contracts.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari Contract..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        @if(isset($vendors))
        <select name="vendor_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Vendor</option>
            @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
            @endforeach
        </select>
        @endif
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="expired" @selected(request('status') == 'expired')>Expired</option>
            <option value="terminated" @selected(request('status') == 'terminated')>Terminated</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('procurement-contracts.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr><th>Contract</th><th>Vendor</th><th>Amount</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($contracts as $row)
                <tr>
                    <td class="font-bold">{{ $row->contract_number }}<br><span class="muted">{{ $row->title }}</span></td>
                    <td>{{ $row->vendor->name ?? '-' }}</td>
                    <td>{{ $rp($row->amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('procurement-contracts.edit-page', $row) }}">Edit</a>
                        <form method="post" action="{{ route('procurement-contracts.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada contract.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($contracts->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $contracts->firstItem() }}-{{ $contracts->lastItem() }} dari {{ $contracts->total() }}</span>
        {{ $contracts->links() }}
    </div>
    @endif
</section>
@endsection
