@extends('layouts.erp', ['activePage' => 'warehouse-create', 'pageTitle' => 'Tambah Gudang'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="warehouse-create">
    <div class="section-head">
        <h2>Tambah Gudang</h2>
    </div>

    <form method="post" action="{{ route('warehouses.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="WH-MAIN" required value="{{ old('code') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Nama
                <input name="name" placeholder="Main Warehouse" required value="{{ old('name') }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Alamat
            <textarea name="address" rows="2" placeholder="Alamat gudang...">{{ old('address') }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Tambah Gudang</button>
            <a href="{{ route('warehouses.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
