@extends('layouts.erp', ['activePage' => 'procurement', 'pageTitle' => 'Procurement Suite'])

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('modules.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-700">Hub Modul</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#002875]">Pengadaan</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Procurement Suite</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola siklus pengadaan: vendor, permintaan pembelian, PO, penerimaan barang, dan kontrak supplier.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 text-[#002875] border border-blue-200 rounded-md">5 Sub Modul</span>
         <x-erp.toolbar-actions module="procurement" :can-create="false" /> </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Vendors</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $vendors->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Supplier rekanan terdaftar</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Purchase Requisition</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $requisitions->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Permintaan pembelian barang</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Purchase Orders</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $orders->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Pesanan pembelian aktif</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Vendor Contracts</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $contracts->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Kontrak pengadaan berjalan</div>
        </div>
    </div>

    <!-- Sub Modules Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('vendors.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Vendor Management</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Database supplier, kategori barang, dan evaluasi vendor.</p>
        </a>

        <a href="{{ route('purchase-requisitions.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Purchase Requisition</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pengajuan kebutuhan barang dan alur persetujuan internal.</p>
        </a>

        <a href="{{ route('purchase-orders.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Purchase Order</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Penerbitan surat pesanan resmi kepada supplier dan tracking pengiriman.</p>
        </a>

        <a href="{{ route('goods-receipts.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Goods Receipt</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Verifikasi fisik barang yang diterima di gudang terhadap pesanan PO.</p>
        </a>

        <a href="{{ route('procurement-contracts.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Procurement Contract</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Dokumen kontrak jangka panjang, syarat pembayaran, dan masa berlaku.</p>
        </a>

        <a href="{{ route('purchase-matches.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">3-Way Matching</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Rekonsiliasi otomatis antara PO, bukti penerimaan barang, dan tagihan vendor.</p>
        </a>
    </div>
</div>
@endsection