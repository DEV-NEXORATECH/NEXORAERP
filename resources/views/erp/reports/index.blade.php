@extends('layouts.erp', ['activePage' => 'reports', 'pageTitle' => 'Report Dashboard'])

@section('content')
@php
    $reportMenus = [
        ['label' => 'Profit & Loss', 'desc' => 'Laporan laba rugi dengan breakdown pendapatan dan biaya per kategori.', 'icon' => 'reports', 'route' => 'reports.profit-loss'],
        ['label' => 'Balance Sheet', 'desc' => 'Neraca aset, liabilities, dan equity dari seluruh jurnal.', 'icon' => 'reports', 'route' => 'reports.balance-sheet'],
        ['label' => 'Cash Flow', 'desc' => 'Arus kas masuk, keluar, dan net cash flow per bulan.', 'icon' => 'reports', 'route' => 'reports.cash-flow'],
        ['label' => 'Project Profitability', 'desc' => 'Profitabilitas per proyek dengan margin, salary, dan invoice total.', 'icon' => 'list', 'route' => 'reports.project'],
        ['label' => 'Aging AR/AP', 'desc' => 'Umur piutang dan utang berdasarkan bucket jatuh tempo.', 'icon' => 'reports', 'url' => route('reports.aging', 'ar')],
        ['label' => 'Tax Summary', 'desc' => 'Rekapitulasi pajak output invoice dan input vendor bill.', 'icon' => 'reports', 'route' => 'reports.tax'],
        ['label' => 'Budget vs Actual', 'desc' => 'Perbandingan anggaran dengan realisasi biaya per periode.', 'icon' => 'reports', 'route' => 'reports.budget'],
        ['label' => 'Transactions', 'desc' => 'Daftar transaksi cashflow dengan filtering dan pagination.', 'icon' => 'cashflow', 'route' => 'reports.transactions'],
        ['label' => 'Reconciliation', 'desc' => 'Rekonsiliasi bank per akun dengan book balance.', 'icon' => 'cashflow', 'route' => 'reports.reconciliation'],
        ['label' => 'Trial Balance', 'desc' => 'Neraca saldo debit/kredit per akun dari seluruh jurnal.', 'icon' => 'reports', 'route' => 'reports.trial-balance'],
        ['label' => 'General Ledger', 'desc' => 'Buku besar per akun dengan saldo berjalan.', 'icon' => 'journal', 'route' => 'reports.general-ledger'],
    ];
@endphp

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('modules.show', 'reports') }}" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] border border-[#D9E2EC] bg-white text-[#475569] transition hover:border-[#0B2345] hover:text-[#0B2345]" title="Kembali ke Hub">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                {!! $icon('reports') !!}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Financial Intelligence</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Reporting Center</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Reports & Analysis</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Pilih laporan keuangan, profitabilitas project, kepatuhan pajak, cashflow, dan rekonsiliasi akun.</p>
            </div>
        </div>

        <div class="inline-flex h-9 items-center gap-1.5 self-start rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Tersedia:</span>
            <span class="font-bold text-[#0B2345]">{{ count($reportMenus) }} Jenis Laporan</span>
        </div>
    </div>

    {{-- Report Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($reportMenus as $item)
            <a
                href="{{ $item['url'] ?? route($item['route']) }}"
                class="group flex min-h-[160px] flex-col justify-between rounded-[12px] border border-[#D9E2EC] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#0B2345] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#0B2345]/20"
            >
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[8px] bg-[#0B2345] text-white shadow-xs">
                            {!! $icon($item['icon']) !!}
                        </div>
                        <div class="min-w-0">
                            <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#0B2345]">
                                {{ $item['label'] }}
                            </h2>
                            <span class="text-[10px] font-medium uppercase tracking-wider text-[#475569]">
                                Financial Ledger
                            </span>
                        </div>
                    </div>

                    <p class="mt-3.5 text-xs font-normal text-[#475569] line-clamp-2">
                        {{ $item['desc'] }}
                    </p>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-[#D9E2EC] pt-3">
                    <span class="text-xs font-semibold text-[#0B2345]">
                        Buka Laporan
                    </span>
                    <span class="text-[#0B2345] transition-transform duration-200 group-hover:translate-x-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Data Export Section --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
        <div class="mb-4 border-b border-[#D9E2EC] pb-3">
            <h2 class="text-sm font-bold text-[#0F172A]">Export Dataset Keuangan</h2>
            <p class="text-xs text-[#475569]">Unduh data transaksi mentah untuk kebutuhan arsip audit dan spreadsheet eksternal.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <a href="{{ route('exports.cashflows') }}" class="group flex items-center justify-between rounded-[8px] border border-[#D9E2EC] p-4 transition hover:border-[#0B2345] hover:bg-[#F8FAFC]">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-[6px] bg-[#E8EEF5] text-[#0B2345]">
                        {!! $icon('backup') !!}
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-[#0F172A] group-hover:text-[#0B2345]">Export Cashflow Records</h3>
                        <p class="text-[11px] text-[#475569]">Download seluruh mutasi cashflow format Excel/CSV.</p>
                    </div>
                </div>
                <span class="btn btn-outline h-7 px-2.5 text-xs font-semibold">Download</span>
            </a>

            <a href="{{ route('exports.project-finance') }}" class="group flex items-center justify-between rounded-[8px] border border-[#D9E2EC] p-4 transition hover:border-[#0B2345] hover:bg-[#F8FAFC]">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-[6px] bg-[#E8EEF5] text-[#0B2345]">
                        {!! $icon('backup') !!}
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-[#0F172A] group-hover:text-[#0B2345]">Export Project Finance</h3>
                        <p class="text-[11px] text-[#475569]">Download laporan komparasi margin & laba proyek.</p>
                    </div>
                </div>
                <span class="btn btn-outline h-7 px-2.5 text-xs font-semibold">Download</span>
            </a>
        </div>
    </div>
</div>
@endsection
