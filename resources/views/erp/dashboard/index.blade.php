@extends('layouts.erp', ['activePage' => 'dashboard', 'pageTitle' => 'Dashboard'])

@section('content')

{{-- Filter chips --}}
<section class="filter-chips section" id="filter-chips">
    <a href="{{ route('projects.index') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('projects') !!}</div>
        <div class="filter-chip-count">{{ $filterCounts['projects_total'] }}</div>
        <div class="filter-chip-label">Projects</div>
        <div class="filter-chip-sub">{{ $filterCounts['projects_active'] }} aktif</div>
    </a>
    @if($can('admin', 'sales'))
    <a href="{{ route('sales.index') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('proposal') !!}</div>
        <div class="filter-chip-count">{{ $filterCounts['proposals_pending'] }}</div>
        <div class="filter-chip-label">Proposals</div>
        <div class="filter-chip-sub">menunggu approval</div>
    </a>
    @endif
    @if($can('admin', 'finance'))
    <a href="{{ route('finance.index') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('invoice') !!}</div>
        <div class="filter-chip-count">{{ $filterCounts['invoices_outstanding'] }}</div>
        <div class="filter-chip-label">Invoices</div>
        <div class="filter-chip-sub">belum lunas</div>
    </a>
    @endif
    @if($can('admin', 'hr'))
    <a href="{{ route('hr.index') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('employees') !!}</div>
        <div class="filter-chip-count">{{ $filterCounts['employees_total'] }}</div>
        <div class="filter-chip-label">Karyawan</div>
        <div class="filter-chip-sub">total terdaftar</div>
    </a>
    @endif
    @if($can('admin', 'finance', 'hr'))
    <a href="{{ route('reimbursements.index-page') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('reimbursement') !!}</div>
        <div class="filter-chip-count">{{ $filterCounts['reimbursements_pending'] }}</div>
        <div class="filter-chip-label">Reimburse</div>
        <div class="filter-chip-sub">pending approval</div>
    </a>
    @endif
    @if($can('admin', 'finance'))
    <a href="{{ route('cashflows.index-page') }}" class="filter-chip">
        <div class="filter-chip-icon">{!! $icon('cashflow') !!}</div>
        <div class="filter-chip-count">{{ $summary['balance'] >= 0 ? '+' : '' }}{{ number_format($summary['balance'] / 1000000, 1) }}M</div>
        <div class="filter-chip-label">Balance</div>
        <div class="filter-chip-sub">net cashflow</div>
    </a>
    @endif
</section>

{{-- Notifications --}}
@if($notifications->isNotEmpty())
<section class="notif-cards section" id="notifications">
    @foreach($notifications as $notification)
    <div class="notif-card {{ $notification['danger'] ? 'danger' : '' }}">
        <div class="notif-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
            <div class="notif-type">{{ $notification['type'] }}</div>
            <div class="notif-count">{{ $notification['count'] }}</div>
            <div class="notif-msg">{{ $notification['message'] }}</div>
        </div>
    </div>
    @endforeach
</section>
@endif

{{-- KPI Row 1 --}}
<section class="grid cards section" id="summary">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
            </div>
            <span class="stat-card-badge up"><svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Bulan ini</span>
        </div>
        <div class="stat-card-metric good">{{ $rp($dashboard['month_income']) }}</div>
        <div class="stat-card-label">Revenue Bulan Ini</div>
        <div class="stat-card-sub"><svg class="h-3.5 w-3.5 text-[#0059A7]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg> {{ now()->format('F Y') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 7 6 6-4 4 8 8"/><path d="M3 3h7v7"/></svg>
            </div>
            <span class="stat-card-badge down"><svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Bulan ini</span>
        </div>
        @php $burnRate = $dashboard['month_income'] > 0 ? round(($dashboard['month_expense'] / $dashboard['month_income']) * 100) : 0; @endphp
        <div class="stat-card-metric bad">{{ $rp($dashboard['month_expense']) }}</div>
        <div class="stat-card-label">Expense Bulan Ini</div>
        <div class="stat-card-sub"><svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg> Burn rate {{ $burnRate }}% dari income</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon warn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>
            </div>
            <span class="stat-card-badge neutral">{{ $filterCounts['invoices_outstanding'] }} invoice</span>
        </div>
        <div class="stat-card-metric bad">{{ $rp($dashboard['outstanding_invoice']) }}</div>
        <div class="stat-card-label">Outstanding Invoice</div>
        <div class="stat-card-sub"><svg class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg> Belum terbayar / partial</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <span class="stat-card-badge neutral">{{ now()->format('M') }}</span>
        </div>
        <div class="stat-card-metric">{{ $rp($dashboard['month_payroll']) }}</div>
        <div class="stat-card-label">Payroll Bulan Ini</div>
        <div class="stat-card-sub"><svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/></svg> {{ $filterCounts['employees_total'] }} karyawan aktif</div>
    </div>
</section>

{{-- KPI Row 2: Cash summary --}}
<section class="grid cards section" id="cash-summary">
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
        <div class="stat-card-metric good">{{ $rp($summary['income']) }}</div>
        <div class="stat-card-label">Total Income</div>
        <div class="stat-card-sub">Semua cashflow income</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top"><div class="stat-card-icon danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
        <div class="stat-card-metric bad">{{ $rp($summary['expense']) }}</div>
        <div class="stat-card-label">Total Expense</div>
        <div class="stat-card-sub">Semua cashflow expense</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon {{ $summary['balance'] >= 0 ? 'success' : 'danger' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-4"/></svg></div>
            <span class="stat-card-badge {{ $summary['balance'] >= 0 ? 'up' : 'down' }}">{{ $summary['balance'] >= 0 ? 'Surplus' : 'Defisit' }}</span>
        </div>
        <div class="stat-card-metric {{ $summary['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($summary['balance']) }}</div>
        <div class="stat-card-label">Net Cash Balance</div>
        <div class="stat-card-sub">Income − Expense</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon warn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg></div>
            <span class="stat-card-badge neutral">{{ $filterCounts['reimbursements_pending'] }} pending</span>
        </div>
        <div class="stat-card-metric">{{ $rp($dashboard['pending_reimbursement']) }}</div>
        <div class="stat-card-label">Reimburse Pending</div>
        <div class="stat-card-sub">Menunggu approval</div>
    </div>
</section>

{{-- Charts --}}
<section class="grid two section">
    <div class="chart-card">
        <div class="chart-header">
            <div><div class="chart-title">Revenue vs Expense</div><div class="chart-sub">6 bulan terakhir berdasarkan cashflow</div></div>
            <span class="badge">Monthly</span>
        </div>
        <div class="mt-6 flex h-64 items-end gap-3 overflow-x-auto">
            @foreach($monthlyChart as $row)
                @php
                    $incomeHeight  = max(4, ($row['income']  / $monthlyMax) * 100);
                    $expenseHeight = max(4, ($row['expense'] / $monthlyMax) * 100);
                @endphp
                <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                    <div class="flex h-52 w-full items-end justify-center gap-1.5 rounded-2xl bg-[#f3f8fc] px-2 py-2">
                        <div title="Income {{ $rp($row['income']) }}" class="w-5 rounded-t-lg bg-[#0059A7] transition-all" style="height: {{ $incomeHeight }}%"></div>
                        <div title="Expense {{ $rp($row['expense']) }}" class="w-5 rounded-t-lg bg-[#b42318] transition-all" style="height: {{ $expenseHeight }}%"></div>
                    </div>
                    <div class="text-[11px] font-black text-slate-500">{{ $row['label'] }}</div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex gap-5 border-t border-[#d7e3ef] pt-4 text-sm font-bold">
            <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#0059A7]"></span> Income</span>
            <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#b42318]"></span> Expense</span>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-header">
            <div><div class="chart-title">Expense Breakdown</div><div class="chart-sub">Komposisi biaya berdasarkan tipe</div></div>
            <span class="badge">Cost Type</span>
        </div>
        <div class="mt-4 grid gap-4">
            @forelse($expenseBreakdown as $item)
                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-3 text-sm font-bold">
                        <span class="capitalize">{{ str_replace('_', ' ', $item['type']) }}</span>
                        <span class="text-slate-500">{{ $item['percent'] }}%</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-[#e8f2fb]">
                        <div class="h-full rounded-full bg-[#0059A7] transition-all" style="width: {{ min(100, $item['percent']) }}%"></div>
                    </div>
                    <div class="mt-1 text-xs font-bold text-slate-500">{{ $rp($item['amount']) }}</div>
                </div>
            @empty
                <div class="rounded-2xl bg-[#f3f8fc] p-6 text-center text-sm font-bold text-slate-500">Belum ada data expense.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Project cashflow bars --}}
<section class="card section">
    <div class="section-head">
        <div><h2>Project Cashflow</h2><p class="muted">Perbandingan income dan expense per project (top 5)</p></div>
        <a class="button ghost" href="{{ route('reports.index') }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-4"/></svg>
            Lihat Reports
        </a>
    </div>
    @forelse($projectChart as $report)
        @php
            $incomeWidth  = max(2, ($report['summary']['income']  / $projectMax) * 100);
            $expenseWidth = max(2, ($report['summary']['expense'] / $projectMax) * 100);
            $marginClass  = $report['profit_margin'] >= 0 ? 'good' : 'bad';
        @endphp
        <div class="mt-4 rounded-2xl border border-[#d7e3ef] bg-[#f8fbfe] p-4">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <a href="{{ route('projects.show', $report['project']) }}" class="font-black text-[#002F59] hover:text-[#0059A7]">{{ $report['project']->code }} — {{ $report['project']->name }}</a>
                    <div class="muted mt-0.5">P/L: <span class="{{ $marginClass }} font-bold">{{ $rp($report['summary']['balance']) }}</span></div>
                </div>
                <span class="badge badge-{{ $report['profit_margin'] >= 0 ? 'approved' : 'rejected' }}">{{ number_format($report['profit_margin'], 1) }}% margin</span>
            </div>
            <div class="grid gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-20 text-xs font-black text-slate-500">Income</div>
                    <div class="h-3 flex-1 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-[#0059A7]" style="width: {{ $incomeWidth }}%"></div></div>
                    <div class="w-32 text-right text-xs font-black good">{{ $rp($report['summary']['income']) }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-20 text-xs font-black text-slate-500">Expense</div>
                    <div class="h-3 flex-1 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-[#b42318]" style="width: {{ $expenseWidth }}%"></div></div>
                    <div class="w-32 text-right text-xs font-black bad">{{ $rp($report['summary']['expense']) }}</div>
                </div>
            </div>
        </div>
    @empty
        <div class="mt-4 rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
    @endforelse
</section>

@endsection
