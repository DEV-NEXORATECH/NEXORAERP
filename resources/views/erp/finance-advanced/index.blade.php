@extends('layouts.erp', ['activePage' => 'finance-advanced', 'pageTitle' => 'Advanced Finance'])

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('modules.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-700">Hub Modul</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#002875]">Keuangan Lanjutan</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Advanced Accounting & Compliance</h1>
            <p class="text-xs text-gray-500 mt-0.5">Fitur lanjutan akuntansi perusahaan: multi-currency, penyusutan aset tetap, aturan perpajakan, rekonsiliasi bank, dan anggaran.</p>
        </div>
    </div>

    <!-- Sub Modules Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('budgets.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Budget Management</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Perencanaan anggaran departemen dan perbandingan realisasi aktual.</p>
        </a>

        <a href="{{ route('fixed-assets.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Fixed Assets</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pencatatan aset tetap perusahaan dan perhitungan beban penyusutan.</p>
        </a>

        <a href="{{ route('tax-rules.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Tax Rules & PPN</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Konfigurasi tarif PPN, PPh pasal 21/23, dan pemotongan pajak faktur.</p>
        </a>

        <a href="{{ route('currencies.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Multi-Currency</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pengaturan mata uang asing, kurs harian, dan pencatatan selisih kurs.</p>
        </a>

        <a href="{{ route('fiscal-periods.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Fiscal Periods</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Penguncian periode tutup buku bulanan dan tahunan akuntansi.</p>
        </a>

        <a href="{{ route('bank-reconciliation-items.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Bank Reconciliation</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pencocokan rekening koran bank dengan catatan transaksi kas internal.</p>
        </a>
    </div>
</div>
@endsection