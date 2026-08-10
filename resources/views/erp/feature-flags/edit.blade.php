@extends('layouts.erp', ['activePage' => 'feature-flags-edit', 'pageTitle' => 'Edit Fitur'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="feature-edit">
    <div class="section-head">
        <h2>Edit Fitur</h2>
    </div>

    <form method="post" action="{{ route('feature-flags.update', $flag) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Key
            <input name="key" placeholder="module.nama-fitur" required value="{{ old('key', $flag->key) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Label
            <input name="label" placeholder="Nama tampilan fitur" required value="{{ old('label', $flag->label) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Modul
            <input name="module" placeholder="accounting / sales / purchase / crm / inventory / contact / hr / reports / cms / platform" required value="{{ old('module', $flag->module) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Deskripsi
            <textarea name="description" rows="2" placeholder="Penjelasan singkat fitur">{{ old('description', $flag->description) }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_enabled" value="1" {{ $flag->is_enabled ? 'checked' : '' }} class="h-4 w-4">
            Fitur aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('feature-flags.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
