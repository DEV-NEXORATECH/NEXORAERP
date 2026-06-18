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
    @if($invoicesPage->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $invoicesPage->firstItem() }}–{{ $invoicesPage->lastItem() }} dari {{ $invoicesPage->total() }}</div>
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
    @if($payments->hasPages())
        <div class="pager">
            <div class="text-sm text-slate-500">{{ $payments->firstItem() }}–{{ $payments->lastItem() }} dari {{ $payments->total() }}</div>
            {{ $payments->links() }}
        </div>
    @endif
</section>
@endsection
