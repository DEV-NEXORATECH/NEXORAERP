@extends('layouts.erp', ['activePage' => 'accounting-journals-edit', 'pageTitle' => 'Edit Journal'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="accounting-journal-edit">
    <div class="section-head">
        <h2>Edit Journal: {{ $journal->name }}</h2>
    </div>

    <form method="post" action="{{ route('accounting-journals.update', $journal) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Kode
            <input name="code" placeholder="JSL" required value="{{ old('code', $journal->code) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Sales Journal" required value="{{ old('name', $journal->name) }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Tipe
            <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                @foreach(['sales', 'purchase', 'bank', 'cash', 'general', 'opening', 'adjustment', 'asset'] as $type)
                    <option value="{{ $type }}" {{ old('type', $journal->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Akun Default
            <select name="default_chart_account_id" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                <option value="">-</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ old('default_chart_account_id', $journal->default_chart_account_id) == $account->id ? 'selected' : '' }}>{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $journal->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('accounting-journals.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
