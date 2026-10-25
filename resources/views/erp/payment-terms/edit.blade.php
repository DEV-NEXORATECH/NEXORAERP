@extends('layouts.erp', ['activePage' => 'payment-terms-edit', 'pageTitle' => 'Edit Payment Term'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="payment-term-edit">
    <div class="section-head">
        <h2>Edit Payment Term: {{ $term->name }}</h2>
    </div>

    <form method="post" action="{{ route('payment-terms.update', $term) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Kode
            <input name="code" placeholder="NET30" required value="{{ old('code', $term->code) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Net 30 Hari" required value="{{ old('name', $term->name) }}">
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tempo (jumlah)
                <input type="number" name="due_days" min="0" max="3660" required value="{{ old('due_days', $term->due_days) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Satuan
                <select name="due_type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                    <option value="day" {{ old('due_type', $term->due_type) === 'day' ? 'selected' : '' }}>Hari</option>
                    <option value="month" {{ old('due_type', $term->due_type) === 'month' ? 'selected' : '' }}>Bulan</option>
                </select>
            </label>
        </div>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $term->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('payment-terms.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
