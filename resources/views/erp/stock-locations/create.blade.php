@extends('layouts.erp', ['activePage' => 'stock-locations', 'pageTitle' => 'Tambah Lokasi Stok'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="stock-location-create">
    <div class="section-head">
        <h2>Tambah Lokasi Stok</h2>
    </div>

    <form method="post" action="{{ route('stock-locations.store') }}" class="grid max-w-2xl gap-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="STK-01" required value="{{ old('code') }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Gudang
                <select name="warehouse_id" required>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected((int) old('warehouse_id') === $warehouse->id)>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Stock Room" required value="{{ old('name') }}">
        </label>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Tipe
            <select name="type" required>
                @foreach(['internal', 'input', 'output', 'transit'] as $type)
                    <option value="{{ $type }}" @selected(old('type', 'internal') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Tambah Lokasi</button>
            <a href="{{ route('stock-locations.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
