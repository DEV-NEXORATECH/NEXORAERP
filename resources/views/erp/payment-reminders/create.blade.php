@extends('layouts.erp', ['activePage' => 'payment-reminder-create', 'pageTitle' => 'Tambah Payment Reminder'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Payment Reminder</h2>
            <p class="muted">Jadwalkan pengingat pembayaran.</p>
        </div>
        <form method="post" action="{{ route('payment-reminders.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5 md:col-span-2"><label>Invoice</label><select name="invoice_id" required>@foreach($unpaidInvoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->number }} - due {{ $invoice->due_date }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Tanggal Reminder</label><input name="reminder_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Channel</label><select name="channel"><option>email</option><option>whatsapp</option><option>phone</option></select></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status"><option>scheduled</option><option>sent</option><option>cancelled</option></select></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Message</label><textarea name="message" placeholder="Template reminder pembayaran"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan
                </button>
                <a class="button ghost" href="{{ route('payment-reminders.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
