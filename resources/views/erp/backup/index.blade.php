@extends('layouts.erp', ['activePage' => 'backup', 'pageTitle' => 'Backup Database'])

@section('content')
<div class="space-y-5 max-w-2xl">
    <x-erp.page-header
        title="Cadangan Database Sistem"
        subtitle="Unduh arsip database sistem untuk kebutuhan disaster recovery dan arsip berkala."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'Database Backup']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions module="backup" :can-create="false" />
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-[#002875]/10 text-[#002875]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-1">Snapshot Database Lokal</h3>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Arsip database ini mencakup seluruh tabel master data, transaksi penjualan, invoice, payroll, jurnal akuntansi, dan log audit. Simpan file cadangan di tempat yang aman.
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('backup.download') }}" class="inline-flex items-center gap-2 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                        Unduh File Database (.sqlite)
                    </a>
                    <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                        Kembali ke Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
