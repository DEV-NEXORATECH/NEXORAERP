@extends('layouts.erp', ['activePage' => 'vendor-bills', 'pageTitle' => 'Vendor Bills'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Accounts Payable & Vendor Bills</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('vendor-bills.create-page') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>
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
            <div class="text-sm text-slate-500">{{ $vendorBills->firstItem() }}–{{ $vendorBills->lastItem() }} dari {{ $vendorBills->total() }}</div>
            {{ $vendorBills->links() }}
        </div>
    @endif
</section>
@endsection
