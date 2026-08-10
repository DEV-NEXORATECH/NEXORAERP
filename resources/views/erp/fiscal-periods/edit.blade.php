@extends('layouts.erp', ['activePage' => 'fiscal-periods-edit', 'pageTitle' => 'Edit Periode Fiskal'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="fiscal-period-edit">
    <div class="section-head">
        <h2>Edit Periode Fiskal: {{ $period->name }}</h2>
    </div>

    <form method="post" action="{{ route('fiscal-periods.update', $period) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="2026-01" required value="{{ old('code', $period->code) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Nama
                <input name="name" placeholder="2026 - Bulan 01" required value="{{ old('name', $period->name) }}">
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal Mulai
                <input type="date" name="start_date" required value="{{ old('start_date', $period->start_date->format('Y-m-d')) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Tanggal Selesai
                <input type="date" name="end_date" required value="{{ old('end_date', $period->end_date->format('Y-m-d')) }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Status
            <select name="status" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700">
                <option value="open" {{ old('status', $period->status) === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ old('status', $period->status) === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('fiscal-periods.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
