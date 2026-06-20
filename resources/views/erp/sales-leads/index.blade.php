@extends('layouts.erp', ['activePage' => 'sales-leads.index', 'pageTitle' => 'Sales Leads Pipeline'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
    <div class="section-head">
        <h2>Daftar Sales Lead</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-leads.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('sales-leads.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="stage" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Stage</option>
            <option value="qualified" {{ request('stage') === 'qualified' ? 'selected' : '' }}>Qualified</option>
            <option value="proposal" {{ request('stage') === 'proposal' ? 'selected' : '' }}>Proposal</option>
            <option value="negotiation" {{ request('stage') === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
            <option value="won" {{ request('stage') === 'won' ? 'selected' : '' }}>Won</option>
            <option value="lost" {{ request('stage') === 'lost' ? 'selected' : '' }}>Lost</option>
        </select>
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('sales-leads.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead>
            <tr><th>Title</th><th>Stage</th><th>Value</th><th>Prob.</th><th>Expected Close</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                <tr>
                    <td class="font-bold">{{ $row->title }}</td>
                    <td><span class="badge badge-{{ $row->stage === 'won' ? 'active' : ($row->stage === 'lost' ? 'void' : 'pending') }}">{{ $row->stage }}</span></td>
                    <td class="font-bold">{{ $rp($row->value) }}</td>
                    <td>{{ $row->probability }}%</td>
                    <td>{{ $row->expected_close_date ? \Carbon\Carbon::parse($row->expected_close_date)->format('d M Y') : '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-leads.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-leads.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada lead.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($items->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }}</span>
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
