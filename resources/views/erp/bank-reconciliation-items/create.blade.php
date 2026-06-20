@extends('layouts.erp', ['activePage' => 'bank-reconciliation-items.create-page', 'pageTitle' => 'Tambah Reconciliation'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Reconciliation Item</h2>
            <p class="muted">Catat item rekonsiliasi bank baru.</p>
        </div>
        <form method="post" action="{{ route('bank-reconciliation-items.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Bank</label>
                <select name="bank_account_id">
                    <option value="">—</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Tanggal Statement</label><input name="statement_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div class="grid gap-1.5"><label>Deskripsi</label><input name="description"></div>
            <div class="grid gap-1.5"><label>Referensi</label><input name="reference"></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" required></div>
            <div class="grid gap-1.5"><label>Tipe</label>
                <select name="type">
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Reconciled</label>
                <select name="reconciled">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Item
                </button>
                <a class="button ghost" href="{{ route('bank-reconciliation-items.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
