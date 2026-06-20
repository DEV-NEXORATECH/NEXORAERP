@extends('layouts.erp', ['activePage' => 'fixed-assets.create-page', 'pageTitle' => 'Tambah Fixed Asset'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Tambah Fixed Asset</h2>
            <p class="muted">Catat aset tetap baru.</p>
        </div>
        <form method="post" action="{{ route('fixed-assets.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf
            <div class="grid gap-1.5"><label>Nama Aset</label><input name="name" required></div>
            <div class="grid gap-1.5"><label>Kode Aset</label><input name="asset_code"></div>
            <div class="grid gap-1.5"><label>Kategori</label><input name="category"></div>
            <div class="grid gap-1.5"><label>Tanggal Perolehan</label><input name="purchase_date" type="date"></div>
            <div class="grid gap-1.5"><label>Harga Perolehan</label><input name="purchase_cost" type="number" min="0" required></div>
            <div class="grid gap-1.5"><label>Nilai Residu</label><input name="residual_value" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Masa Manfaat (tahun)</label><input name="useful_life_years" type="number" min="1"></div>
            <div class="grid gap-1.5"><label>Metode Depresiasi</label>
                <select name="depreciation_method">
                    <option value="">—</option>
                    <option value="straight_line">Garis Lurus</option>
                    <option value="declining">Saldo Menurun</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Depresiasi Bulanan</label><input name="monthly_depreciation" type="number" min="0" step="0.01"></div>
            <div class="grid gap-1.5"><label>Akumulasi Depresiasi</label><input name="accumulated_depreciation" type="number" min="0" value="0"></div>
            <div class="grid gap-1.5"><label>Nilai Buku</label><input name="book_value" type="number" min="0" step="0.01"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="disposed">Disposed</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Asset
                </button>
                <a class="button ghost" href="{{ route('fixed-assets.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
