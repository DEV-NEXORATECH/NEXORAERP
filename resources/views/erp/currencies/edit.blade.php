@extends('layouts.erp', ['activePage' => 'currencies-edit', 'pageTitle' => 'Edit Mata Uang'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="currency-edit">
    <div class="section-head">
        <h2>Edit Mata Uang: {{ $currency->code }}</h2>
    </div>

    <form method="post" action="{{ route('currencies.update', $currency) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="IDR" maxlength="8" required value="{{ old('code', $currency->code) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Simbol
                <input name="symbol" placeholder="Rp" maxlength="8" value="{{ old('symbol', $currency->symbol) }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Indonesian Rupiah" required value="{{ old('name', $currency->name) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Rate (nilai 1 unit terhadap IDR)
            <input type="number" name="rate" step="0.000001" min="0.000001" required value="{{ old('rate', $currency->rate) }}">
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $currency->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('currencies.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
