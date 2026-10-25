@extends('layouts.erp', ['activePage' => 'fixed-assets.create-page', 'pageTitle' => 'Tambah Fixed Asset'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Tambah Aset</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('fixed-assets.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('fixed-assets.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf
            <div class="grid gap-1.5"><label>Nama Aset</label><input name="name" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Kode Aset</label><input name="asset_code" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Kategori</label><input name="category" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Tanggal Perolehan</label><input name="purchase_date" type="date" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Harga Perolehan</label><input name="purchase_cost" type="number" min="0" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Nilai Residu</label><input name="residual_value" type="number" min="0" value="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Masa Manfaat (tahun)</label><input name="useful_life_years" type="number" min="1" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Metode Depresiasi</label>
                <select name="depreciation_method" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="">—</option>
                    <option value="straight_line">Garis Lurus</option>
                    <option value="declining">Saldo Menurun</option>
                </select>
            </div>
            <div class="grid gap-1.5"><label>Depresiasi Bulanan</label><input name="monthly_depreciation" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Akumulasi Depresiasi</label><input name="accumulated_depreciation" type="number" min="0" value="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Nilai Buku</label><input name="book_value" type="number" min="0" step="0.01" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                    <option value="active">Active</option>
                    <option value="disposed">Disposed</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Asset
                </button>
                <a class="button ghost" href="{{ route('fixed-assets.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('fixed-assets.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Simpan Asset</button>
            </div>
        </form>
    </div>
</div>
@endsection
