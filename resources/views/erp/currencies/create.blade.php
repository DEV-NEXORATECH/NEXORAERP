@extends('layouts.erp', ['activePage' => 'currencies-create', 'pageTitle' => 'Tambah Mata Uang'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="currency-create">
    <div class="section-head">
        <h2>Tambah Mata Uang</h2>
    </div>

    <form method="post" action="{{ route('currencies.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="IDR" maxlength="8" required value="{{ old('code') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Simbol
                <input name="symbol" placeholder="Rp" maxlength="8" value="{{ old('symbol') }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Indonesian Rupiah" required value="{{ old('name') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Rate (nilai 1 unit terhadap IDR)
            <input type="number" name="rate" step="0.000001" min="0.000001" required value="{{ old('rate', 1) }}">
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Tambah Mata Uang</button>
            <a href="{{ route('currencies.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
