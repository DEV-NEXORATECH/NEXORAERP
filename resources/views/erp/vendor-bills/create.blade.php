@extends('layouts.erp', ['activePage' => 'vendor-bill-create', 'pageTitle' => 'Tambah Vendor Bill'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Vendor Bill</h2>
            <p class="muted">Catat tagihan vendor baru.</p>
        </div>
        <form method="post" action="{{ route('vendor-bills.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Vendor</label><input name="vendor_name" required></div>
            <div class="grid gap-1.5"><label>Bill Number</label><input name="bill_number" placeholder="Auto jika kosong"></div>
            <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Company</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Bank/Kas</label><select name="bank_account_id"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Bill Date</label><input name="bill_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Due Date</label><input name="due_date" type="date"></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Tax %</label><input name="tax_rate" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status"><option>unpaid</option><option>partial</option><option>paid</option><option>void</option></select></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><input name="notes"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan
                </button>
                <a class="button ghost" href="{{ route('vendor-bills.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
