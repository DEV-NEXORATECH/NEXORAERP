@extends('layouts.erp', ['activePage' => 'vendor-payments', 'pageTitle' => 'Vendor Payments'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="vendor-payments">
    <div class="section-head">
        <h2>Vendor Bill Payment Scheduling</h2>
        @if($can('admin', 'finance'))
        <a class="button ghost" href="{{ route('vendor-payments.create-page') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('vendor-payments.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('vendor-payments.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Tanggal</th><th>Vendor Bill</th><th>Bank/Kas</th><th>Amount</th><th>Reference</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date }}</td>
                    <td class="font-bold">{{ $payment->vendorBill?->bill_number ?? '-' }}<br><span class="muted">{{ $payment->vendorBill?->vendor_name ?? '' }}</span></td>
                    <td>{{ $payment->bankAccount?->name ?? '-' }}</td>
                    <td class="bad font-bold">{{ $rp($payment->amount) }}</td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td class="actions">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('vendor-payments.edit-page', $payment) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('vendor-payments.destroy', $payment) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada vendor payment.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($payments->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $payments->firstItem() }}-{{ $payments->lastItem() }} dari {{ $payments->total() }}</span>
        {{ $payments->links() }}
    </div>
    @endif
</section>
@endsection
