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
    ];
@endphp

<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('modules.show', 'reports') }}">Back to Hub</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('reports') !!}</span>
                <div>
                    <span class="module-eyebrow">Financial Intelligence</span>
                    <h1>Reports & Analysis</h1>
                </div>
            </div>
            <p>Pilih laporan finance, project, pajak, cashflow, aging, dan reconciliation yang ingin dibuka.</p>
        </div>
        <div class="module-count">
            <strong>{{ count($reportMenus) }}</strong>
            <span>Report</span>
        </div>
    </div>
</section>

<section class="module-sub-grid">
    @foreach($reportMenus as $item)
        <a href="{{ $item['url'] ?? route($item['route']) }}" class="module-sub-card">
            <div class="module-sub-main">
                <span class="module-sub-icon">{!! $icon($item['icon']) !!}</span>
                <div>
                    <h3>{{ $item['label'] }}</h3>
                    <p>{{ $item['desc'] }}</p>
                </div>
            </div>
            <span class="module-sub-action">Open</span>
        </a>
    @endforeach
</section>

<section class="module-sub-grid">
    <a class="module-sub-card" href="{{ route('exports.cashflows') }}">
        <div class="module-sub-main">
            <span class="module-sub-icon">{!! $icon('backup') !!}</span>
            <div>
                <h3>Export Cashflows</h3>
                <p>Download laporan cashflow untuk kebutuhan spreadsheet atau arsip.</p>
            </div>
        </div>
        <span class="module-sub-action">Export</span>
    </a>
    <a class="module-sub-card" href="{{ route('exports.project-finance') }}">
        <div class="module-sub-main">
            <span class="module-sub-icon">{!! $icon('backup') !!}</span>
            <div>
                <h3>Export Project Finance</h3>
                <p>Download laporan finance project untuk analisa profitabilitas.</p>
            </div>
        </div>
        <span class="module-sub-action">Export</span>
    </a>
</section>
@endsection
