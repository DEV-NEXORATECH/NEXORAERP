@extends('layouts.erp', ['activePage' => 'currency-variances.create-page', 'pageTitle' => 'Tambah Currency Variance'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Currency Variance</h2>
            <p class="muted">Catat selisih kurs baru.</p>
        </div>
        <form method="post" action="{{ route('currency-variances.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Rate</label>
                <select name="rate_id" required>
                    <option value="">—</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->id }}">{{ $rate->from_currency }}→{{ $rate->to_currency }} ({{ $rate->rate_date }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Variance %</label><input name="variance_percent" type="number" step="0.01"></div>
            <div class="grid gap-1.5"><label>Variance Amount</label><input name="variance_amount" type="number" step="0.01"></div>
            <div class="grid gap-1.5"><label>Periode</label><input name="period" placeholder="2026-06"></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Variance
                </button>
                <a class="button ghost" href="{{ route('currency-variances.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
