@extends('layouts.erp', ['activePage' => 'sales-commissions.index', 'pageTitle' => 'Sales Commissions'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5">
    <div class="section-head">
        <h2>Daftar Sales Commission</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-commissions.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('sales-commissions.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        <select name="user_id" class="field-input">
            <option value="">Semua User</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('sales-commissions.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>
    <table>
        <thead>
            <tr><th>User</th><th>Period</th><th>Base Amount</th><th>Rate</th><th>Commission</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                <tr>
                    <td class="font-bold">{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->period }}</td>
                    <td>{{ $rp($row->base_amount) }}</td>
                    <td>{{ $row->rate }}%</td>
                    <td class="font-bold">{{ $rp($row->commission_amount) }}</td>
                    <td><span class="badge badge-{{ $row->status === 'paid' ? 'active' : ($row->status === 'draft' ? 'pending' : 'void') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-commissions.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-commissions.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada komisi.</td></tr>
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
