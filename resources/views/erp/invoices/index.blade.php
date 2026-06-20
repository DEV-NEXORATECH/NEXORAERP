@extends('layouts.erp', ['activePage' => 'invoices', 'pageTitle' => 'Invoice & Payment'])

@section('content')
<section class="card section wide">
    <div class="section-head">
        <h2>Invoice & Payment</h2>
        @if($can('admin', 'finance'))
        <div class="flex gap-2">
            <a class="button ghost" href="{{ route('invoices.create-page') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Buat Invoice
            </a>
            <a class="button ghost" href="{{ route('payments.create-page') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Catat Payment
            </a>
        </div>
        @endif
    </div>
    <form method="get" action="{{ route('finance.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status')==='draft')>Draft</option>
            <option value="sent" @selected(request('status')==='sent')>Sent</option>
            <option value="partial" @selected(request('status')==='partial')>Partial</option>
            <option value="paid" @selected(request('status')==='paid')>Paid</option>
            <option value="void" @selected(request('status')==='void')>Void</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('finance.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Invoice</th><th>Status</th><th>Terbayar / Total</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($invoicesPage as $invoice)
                <tr>
                    <td>
                        <div class="font-black">{{ $invoice->number }}</div>
                        <div class="muted">{{ $invoice->project->code }} · due {{ $invoice->due_date }}</div>
                    </td>
                    <td><span class="badge badge-{{ $invoice->status }}">{{ $invoice->status }}</span></td>
                    <td>
                        <div class="good font-bold">{{ $rp($invoice->paid_amount) }}</div>
                        <div class="muted">dari {{ $rp($invoice->amount) }}</div>
                    </td>
                    <td class="actions">
                        <a class="button mini ghost" href="{{ route('invoices.pdf', $invoice) }}" target="_blank">Lihat</a>
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('invoices.edit-page', $invoice) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('invoices.destroy', $invoice) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada invoice.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($invoicesPage->hasPages() || $invoicesPage->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $invoicesPage->firstItem() }}-{{ $invoicesPage->lastItem() }} dari {{ $invoicesPage->total() }}</span>
            {{ $invoicesPage->links() }}
        </div>
    @endif

    <h3 class="mt-6">Riwayat Payments</h3>
    <table>
        <thead><tr><th>Tanggal</th><th>Invoice</th><th>Bank/Kas</th><th>Amount</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date }}</td>
                    <td class="font-bold">{{ $payment->invoice->number }}</td>
                    <td class="muted">{{ $payment->bankAccount?->name ?? '-' }}</td>
                    <td class="good font-bold">{{ $rp($payment->amount) }}</td>
                    <td>@if($can('admin'))<form method="post" action="{{ route('payments.destroy', $payment) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif</td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada payment.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages() || $payments->total() > 0)
        <div class="pager">
            <span>Menampilkan {{ $payments->firstItem() }}-{{ $payments->lastItem() }} dari {{ $payments->total() }}</span>
            {{ $payments->links() }}
        </div>
    @endif
</section>
@endsection
