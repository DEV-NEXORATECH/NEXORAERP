@extends('layouts.erp', ['activePage' => 'vendor-bills', 'pageTitle' => 'Vendor Bills'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="vendor-bills">
    <div class="section-head">
        <h2>Accounts Payable & Vendor Bills</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('vendor-bills.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('vendor-bills.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <select name="status" class="field-input">
            <option value="">Semua Status</option>
            <option value="unpaid" @selected(request('status') == 'unpaid')>Unpaid</option>
            <option value="partial" @selected(request('status') == 'partial')>Partial</option>
            <option value="paid" @selected(request('status') == 'paid')>Paid</option>
            <option value="void" @selected(request('status') == 'void')>Void</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('vendor-bills.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Bill #</th><th>Vendor</th><th>Project</th><th>Amount</th><th>Paid</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($vendorBills as $bill)
                <tr>
                    <td class="font-bold">{{ $bill->bill_number }}</td>
                    <td>{{ $bill->vendor_name }}</td>
                    <td>{{ $bill->project?->code ?? 'Company' }}</td>
                    <td>{{ $rp($bill->amount) }}</td>
                    <td class="good">{{ $rp($bill->paid_amount) }}</td>
                    <td><span class="badge badge-{{ $bill->status === 'unpaid' ? 'pending' : $bill->status }}">{{ $bill->status }}</span></td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('vendor-bills.edit-page', $bill) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('vendor-bills.destroy', $bill) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-slate-500">Belum ada vendor bill.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($vendorBills->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $vendorBills->firstItem() }}-{{ $vendorBills->lastItem() }} dari {{ $vendorBills->total() }}</span>
        {{ $vendorBills->links() }}
    </div>
    @endif
</section>
@endsection
