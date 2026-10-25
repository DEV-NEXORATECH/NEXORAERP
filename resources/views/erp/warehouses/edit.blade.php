@extends('layouts.erp', ['activePage' => 'warehouse-edit', 'pageTitle' => 'Edit Gudang'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="warehouse-edit">
    <div class="section-head">
        <h2>Edit Gudang: {{ $warehouse->code }}</h2>
    </div>

    <form method="post" action="{{ route('warehouses.update', $warehouse) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="WH-MAIN" required value="{{ old('code', $warehouse->code) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Nama
                <input name="name" placeholder="Main Warehouse" required value="{{ old('name', $warehouse->name) }}">
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Alamat
            <textarea name="address" rows="2" placeholder="Alamat gudang...">{{ old('address', $warehouse->address) }}</textarea>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $warehouse->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('warehouses.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
