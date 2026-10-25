<?php

// 1. reports/aging.blade.php
$aging = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-aging', 'pageTitle' => 'Aging AR/AP Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Aging {{ strtoupper($type) }}</h1>
            <p class="text-xs text-gray-500">Laporan umur {{ $type === 'ar' ? 'piutang' : 'utang' }} berdasarkan bucket jatuh tempo.</p>
        </div>
        <x-erp.toolbar-actions module="report-aging-{{ $type }}" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <div class="filter-toolbar">
            <span class="text-xs font-semibold text-gray-500 mr-1">Tipe:</span>
            <a class="px-3 py-1.5 text-xs font-semibold rounded-md transition {{ $type === 'ar' ? 'bg-[#002875] text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}" href="{{ route('reports.aging', 'ar') }}">Aging AR (Piutang)</a>
            <a class="px-3 py-1.5 text-xs font-semibold rounded-md transition {{ $type === 'ap' ? 'bg-[#002875] text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}" href="{{ route('reports.aging', 'ap') }}">Aging AP (Utang)</a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($aging['buckets'] as $bucket => $amount)
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-500">{{ str_replace('_', '-', $bucket) }} hari</div>
                <div class="text-lg font-bold text-gray-900 mt-1">{{ $rp($amount) }}</div>
            </div>
        @endforeach
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">{{ $type === 'ar' ? 'Laporan Umur Piutang' : 'Laporan Umur Utang' }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">No</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Due Date</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Days</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bucket</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Outstanding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($aging['items'] as $item)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 text-left font-mono">{{ $item['row']->number ?? $item['row']->bill_number }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $item['row']->due_date }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700 text-right">{{ $item['days'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="rounded-full px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700">{{ $item['bucket'] }}</span></td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-bold text-right">{{ $rp($item['outstanding']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data aging.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/aging.blade.php', $aging);

// 2. reports/balance-sheet.blade.php
$balanceSheet = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-balance-sheet', 'pageTitle' => 'Balance Sheet Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Balance Sheet (Neraca)</h1>
            <p class="text-xs text-gray-500">Neraca aset, liabilities, dan equity dari seluruh jurnal transaksi.</p>
        </div>
        <x-erp.toolbar-actions module="report-balance-sheet" :can-create="false" />
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach(['assets' => 'Assets', 'liabilities' => 'Liabilities', 'equity' => 'Equity'] as $key => $label)
            <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
                <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">{{ $label }}</h2>
                </div>
                <div class="p-3">
                    <table class="table-folk w-full text-left border-collapse text-xs">
                        <tbody class="divide-y divide-gray-200">
                            @forelse($balanceSheet[$key] as $row)
                                <tr>
                                    <td class="px-3 py-2 text-xs text-gray-700">{{ $row['account']->code }} - {{ $row['account']->name }}</td>
                                    <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($row['balance']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada jurnal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Balance Check Card --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs p-4">
        <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Balance Check</h2>
        <table class="table-folk w-full text-left border-collapse text-xs">
            <tbody>
                <tr>
                    <td class="px-3 py-2.5 font-semibold text-gray-700">Total Assets</td>
                    <td class="px-3 py-2.5 text-right font-black text-gray-900 text-sm">{{ $rp($balanceSheet['total_assets']) }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2.5 font-semibold text-gray-700">Liabilities + Equity</td>
                    <td class="px-3 py-2.5 text-right font-black text-gray-900 text-sm">{{ $rp($balanceSheet['total_liabilities'] + $balanceSheet['total_equity']) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/balance-sheet.blade.php', $balanceSheet);

// 3. reports/budget.blade.php
$budget = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-budget', 'pageTitle' => 'Budget vs Actual Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Budget vs Actual</h1>
            <p class="text-xs text-gray-500">Perbandingan anggaran dengan realisasi biaya per periode.</p>
        </div>
        <x-erp.toolbar-actions module="report-budget" :can-create="false" />
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Anggaran vs Realisasi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Period</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Project / Account</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Budget</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Actual</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Variance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($budgetVsActual as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $row['budget']->period }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                <div class="font-bold">{{ $row['budget']->project?->code ?? 'Company' }}</div>
                                <div class="text-[11px] text-gray-500">{{ $row['budget']->account?->code }} {{ $row['budget']->account?->name }}</div>
                            </td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['budget']->budget_amount) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['actual']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold {{ $row['variance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $rp($row['variance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data anggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/budget.blade.php', $budget);

// 4. reports/cash-flow.blade.php
$cashFlow = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-cash-flow', 'pageTitle' => 'Cash Flow Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Cash Flow</h1>
            <p class="text-xs text-gray-500">Arus kas masuk, keluar, dan net cash flow per bulan.</p>
        </div>
        <x-erp.toolbar-actions module="report-cashflow" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.cash-flow') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.cash-flow') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Cash In</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $rp($cashFlowStatement['operating_in']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Cash Out</div>
            <div class="text-xl font-bold text-rose-600 mt-1">{{ $rp($cashFlowStatement['operating_out']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Net Cash Flow</div>
            <div class="text-xl font-bold {{ $cashFlowStatement['net_cash'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">{{ $rp($cashFlowStatement['net_cash']) }}</div>
        </div>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Cash Flow by Month</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bulan</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Income</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Expense</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cashFlowStatement['by_month'] as $month => $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-900 font-semibold">{{ $month }}</td>
                            <td class="px-3 py-2 text-xs text-right text-emerald-600">{{ $rp($row['income']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-rose-600">{{ $rp($row['expense']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold {{ $row['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $rp($row['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">Tidak ada data arus kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/cash-flow.blade.php', $cashFlow);

// 5. reports/general-ledger.blade.php
$gl = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-general-ledger', 'pageTitle' => 'General Ledger'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">General Ledger (Buku Besar)</h1>
            <p class="text-xs text-gray-500">Buku besar per akun dengan saldo berjalan untuk periode terpilih.</p>
        </div>
        <x-erp.toolbar-actions module="report-general-ledger" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.general-ledger') }}" class="filter-toolbar">
            <select name="chart_account_id" class="filter-select">
                <option value="">Semua Akun</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" @selected((int) request('chart_account_id') === $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.general-ledger') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">General Ledger Entries</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Akun</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">No. Jurnal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Deskripsi</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Debit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Kredit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($paginated as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $row['account_code'] }} - {{ $row['account_name'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700 whitespace-nowrap">{{ $row['date'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $row['journal_code'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $row['description'] }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['debit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['credit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($row['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">Tidak ada mutasi untuk filter tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/general-ledger.blade.php', $gl);

// 6. reports/profit-loss.blade.php
$pl = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-profit-loss', 'pageTitle' => 'Profit & Loss Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Profit & Loss (Laba Rugi)</h1>
            <p class="text-xs text-gray-500">Laporan laba rugi dengan breakdown pendapatan dan biaya per kategori.</p>
        </div>
        <x-erp.toolbar-actions module="report-profit-loss" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.profit-loss') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.profit-loss') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Revenue</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $rp($profitLoss['revenue']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Expense</div>
            <div class="text-xl font-bold text-rose-600 mt-1">{{ $rp($profitLoss['expense']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs font-medium text-gray-500">Net Profit / Loss</div>
            <div class="text-xl font-bold {{ $profitLoss['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">{{ $rp($profitLoss['net_profit']) }}</div>
        </div>
    </div>

    {{-- Two Column Detail --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Expense by Category</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($profitLoss['expense_by_category'] as $category => $amount)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-700">{{ $category }}</td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada expense.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Revenue by Category</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($profitLoss['revenue_by_category'] as $category => $amount)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-700">{{ $category }}</td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada revenue.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/profit-loss.blade.php', $pl);

// 7. reports/project.blade.php
$proj = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-project', 'pageTitle' => 'Project Profitability Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Project Profitability</h1>
            <p class="text-xs text-gray-500">Profitabilitas per proyek dengan margin, salary, dan invoice total.</p>
        </div>
        <x-erp.toolbar-actions module="report-project" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.project') }}" class="filter-toolbar">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.project') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Projects Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse ($projectReports as $report)
            <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs p-4">
                <h3 class="font-bold text-sm text-gray-900 mb-3 border-b border-gray-100 pb-2">{{ $report['project']->code }} - {{ $report['project']->name }}</h3>
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody>
                        <tr><td class="px-2.5 py-1.5 text-gray-600">Kontrak</td><td class="px-2.5 py-1.5 text-right font-bold text-gray-900">{{ $rp($report['project']->contract_value) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-gray-600">Income</td><td class="px-2.5 py-1.5 text-emerald-600 text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-gray-600">Expense</td><td class="px-2.5 py-1.5 text-rose-600 text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-gray-600">Profit/Loss</td><td class="px-2.5 py-1.5 {{ $report['summary']['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-gray-600">Margin</td><td class="px-2.5 py-1.5 text-right font-bold text-gray-900">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                    </tbody>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-lg bg-white p-8 text-center text-xs font-semibold text-gray-500 border border-gray-200">Belum ada data project.</div>
        @endforelse
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/project.blade.php', $proj);

// 8. reports/reconciliation.blade.php
$recon = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-reconciliation', 'pageTitle' => 'Bank Reconciliation Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Bank Reconciliation</h1>
            <p class="text-xs text-gray-500">Rekonsiliasi bank per akun dengan book balance.</p>
        </div>
        <x-erp.toolbar-actions module="report-reconciliation" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.reconciliation') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.reconciliation') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Bank Reconciliation Status</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bank/Kas</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Opening</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Cash In</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Cash Out</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Book Balance</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Unreconciled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bankReconciliation as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-900 font-bold">
                                {{ $row['bank']->name }}
                                <div class="text-[11px] text-gray-500 font-normal">{{ $row['bank']->bank_name }} {{ $row['bank']->account_number }}</div>
                            </td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['bank']->opening_balance) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-emerald-600">{{ $rp($row['income']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-rose-600">{{ $rp($row['expense']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-black text-gray-900">{{ $rp($row['book_balance']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-amber-600">{{ $rp($row['unreconciled']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data rekonsiliasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/reconciliation.blade.php', $recon);

// 9. reports/tax.blade.php
$tax = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-tax', 'pageTitle' => 'Tax Summary Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Tax Summary (Rekap Pajak)</h1>
            <p class="text-xs text-gray-500">Rekapitulasi pajak output (invoice) dan input (vendor bill).</p>
        </div>
        <x-erp.toolbar-actions module="report-tax" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.tax') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.tax') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Recap & Active Rules --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Tax Recap</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-3 py-2.5 text-gray-700">Output Tax dari Invoice</td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-600">{{ $rp($taxSummary['invoice_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-gray-700">Input/Withholding Tax dari Vendor Bill</td>
                            <td class="px-3 py-2.5 text-right font-bold text-rose-600">{{ $rp($taxSummary['vendor_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-gray-900 font-bold border-t-2 border-gray-300">Net Tax</td>
                            <td class="px-3 py-2.5 text-right font-black text-gray-900 border-t-2 border-gray-300">{{ $rp($taxSummary['net_tax']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Active Tax Rules</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($taxSummary['rules'] as $rule)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-700">
                                    <div class="font-bold">{{ $rule->name }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $rule->tax_type }} - {{ $rule->direction }}</div>
                                </td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rule->rate }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada aturan pajak aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/tax.blade.php', $tax);

// 10. reports/transactions.blade.php
$tx = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-transactions', 'pageTitle' => 'Transaction Listings'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Transaction Listings</h1>
            <p class="text-xs text-gray-500">Daftar transaksi cashflow dengan filtering dan pagination.</p>
        </div>
        <x-erp.toolbar-actions module="report-transactions" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.transactions') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.transactions') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Tanggal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Project</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Type</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Category</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bank</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $flow)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 whitespace-nowrap">{{ $flow->transaction_date }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->project?->code ?? 'Company' }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $flow->type === 'in' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $flow->type }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->category }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">{{ $flow->bankAccount?->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($flow->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4 — Standalone Pagination --}}
    <x-erp.pagination :paginator="$transactions" />
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/transactions.blade.php', $tx);

// 11. reports/trial-balance.blade.php
$tb = <<<'BLADE'
@extends('layouts.erp', ['activePage' => 'reports-trial-balance', 'pageTitle' => 'Trial Balance'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Trial Balance (Neraca Saldo)</h1>
            <p class="text-xs text-gray-500">Neraca saldo per akun berdasarkan jurnal pada periode terpilih.</p>
        </div>
        <x-erp.toolbar-actions module="report-trial-balance" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.trial-balance') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="type" class="filter-select">
                <option value="">Semua Tipe Akun</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.trial-balance') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Trial Balance Entries</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kode</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Akun</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Type</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Debit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Kredit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $account['code'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-medium">{{ $account['name'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">{{ $account['type'] }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($account['debit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($account['credit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($account['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada jurnal pada periode tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE;
file_put_contents(dirname(__DIR__) . '/resources/views/erp/reports/trial-balance.blade.php', $tb);

echo "Standardized all 11 reports successfully!\n";
