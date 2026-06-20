@extends('layouts.erp', ['activePage' => 'sales', 'pageTitle' => 'Sales & Proposals'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Proposal Workflow</h2>
        <a class="button ghost" href="{{ route('proposals.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
    </div>
    <form method="get" action="{{ route('sales.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status')==='draft')>Draft</option>
            <option value="sent" @selected(request('status')==='sent')>Sent</option>
            <option value="approved" @selected(request('status')==='approved')>Approved</option>
            <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('sales.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Proposal</th><th>Status</th><th>Amount</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($proposalsPage as $proposal)
                <tr>
                    <td>
                        <div class="font-bold">{{ $proposal->title }}</div>
                        <div class="muted">{{ $proposal->project->code }}</div>
                    </td>
                    <td>
                        <form method="post" action="{{ route('proposals.status', $proposal) }}" class="toolbar">
                            @csrf @method('patch')
                            <select name="status" class="w-auto min-h-9 py-1.5 text-xs">
                                @foreach($statusOptions['proposal'] as $status)<option @selected($proposal->status===$status)>{{ $status }}</option>@endforeach
                            </select>
                            <button class="mini">Simpan</button>
                        </form>
                    </td>
                    <td class="font-bold">{{ $rp($proposal->amount) }}</td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('proposals.pdf', $proposal) }}" target="_blank">Lihat</a>
                        <a class="button mini ghost" href="{{ route('proposals.edit-page', $proposal) }}">Edit</a>
                        @if($can('admin'))<form method="post" action="{{ route('proposals.destroy', $proposal) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada proposal.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($proposalsPage->hasPages() || $proposalsPage->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $proposalsPage->firstItem() }}-{{ $proposalsPage->lastItem() }} dari {{ $proposalsPage->total() }}</span>
            {{ $proposalsPage->links() }}
        </div>
    @endif
</section>
@endsection
