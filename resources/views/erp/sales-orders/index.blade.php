@extends('layouts.erp', ['activePage' => 'sales-orders.index', 'pageTitle' => 'Sales Orders'])

@section('content')
<div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5">
    <div class="section-head">
        <h2>Daftar Sales Order</h2>
        @if($can('admin', 'sales'))
        <a class="button ghost" href="{{ route('sales-orders.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
    <form method="get" action="{{ route('sales-orders.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('sales-orders.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>
    <table>
        <thead>
            <tr><th>Number</th><th>Title</th><th>Amount</th><th>Items</th><th>Order Date</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                <tr>
                    <td class="font-bold">{{ $row->number }}</td>
                    <td>{{ $row->title }}</td>
                    <td class="font-bold">{{ $rp($row->amount) }}</td>
                    <td>{{ $row->items_count }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->order_date)->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'confirmed' ? 'active' : ($row->status === 'cancelled' ? 'void' : 'pending') }}">{{ $row->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'sales'))<a class="button mini ghost" href="{{ route('sales-orders.edit-page', $row) }}">Edit</a>@endif
                        @if($can('admin', 'sales'))<form method="post" action="{{ route('sales-orders.destroy', $row) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada sales order.</td></tr>
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
