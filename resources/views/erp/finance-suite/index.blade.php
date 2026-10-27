@extends('layouts.erp', ['activePage' => 'finance-suite', 'pageTitle' => 'Finance Suite'])

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('modules.index') }}" class="text-xs font-medium text-[#475569] hover:text-[#334155]">Hub Modul</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#002875]">Keuangan</span>
            </div>
            <h1 class="text-xl font-bold text-[#0F172A] tracking-tight">Finance & Accounting Suite</h1>
            <p class="text-xs text-[#475569] mt-0.5">Pusat kendali arus kas, piutang, hutang, buku besar, anggaran, dan pelaporan keuangan terpadu.</p>
        </div>
        {{-- Export/Import/PDF tersedia di halaman fitur masing-masing --}}
    </div>

    <!-- Sub Modules Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('finance.invoices.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Invoices & Piutang (AR)</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Faktur penjualan customer, pelacakan jatuh tempo, dan pencatatan pembayaran.</p>
        </a>

        <a href="{{ route('vendor-bills.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Tagihan Vendor (AP)</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Faktur dari supplier rekanan, verifikasi tagihan, dan penjadwalan pembayaran hutang.</p>
        </a>

        <a href="{{ route('chart-accounts.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Chart of Accounts (CoA)</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Struktur bagan akun standar akuntansi: aktiva, kewajiban, ekuitas, pendapatan, dan beban.</p>
        </a>

        <a href="{{ route('journal-entries.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Journal Entries</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Pencatatan ayat jurnal umum debet-kredit yang seimbang dan dapat diaudit.</p>
        </a>

        <a href="{{ route('bank-accounts.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Kas & Rekening Bank</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Daftar rekening bank operasional, petty cash, dan mutasi saldo.</p>
        </a>

        <a href="{{ route('reports.index') }}" class="group bg-white p-4 rounded-lg border border-[#D1D5DB] hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#002875] transition">Laporan Keuangan</h3>
                <svg class="w-4 h-4 text-[#94A3B8] group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-[#475569] mt-1">Laba rugi, neraca, arus kas, trial balance, dan ledger komprehensif.</p>
        </a>
    </div>
</div>
@endsection