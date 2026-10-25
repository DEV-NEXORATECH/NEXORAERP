@extends('layouts.erp', ['activePage' => 'feature-flags-create', 'pageTitle' => 'Tambah Fitur'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="feature-create">
    <div class="section-head">
        <h2>Tambah Fitur Baru</h2>
    </div>

    <form method="post" action="{{ route('feature-flags.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Key
            <input name="key" placeholder="module.nama-fitur" required value="{{ old('key') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Label
            <input name="label" placeholder="Nama tampilan fitur" required value="{{ old('label') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Modul
            <input name="module" placeholder="accounting / sales / purchase / crm / inventory / contact / hr / reports / cms / platform" required value="{{ old('module', 'platform') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Deskripsi
            <textarea name="description" rows="2" placeholder="Penjelasan singkat fitur">{{ old('description') }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_enabled" value="1" checked class="h-4 w-4">
            Aktif sejak awal
        </label>
        <div class="flex gap-3">
            <button>Tambah Fitur</button>
            <a href="{{ route('feature-flags.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
