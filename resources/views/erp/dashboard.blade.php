@extends('layouts.app', ['title' => 'NEXORA ERP Dashboard'])

@php
    $role = auth()->user()->role;
    $can = fn (...$roles) => $role === 'admin' || in_array($role, $roles, true);
    $rp = fn ($value) => 'Rp '.number_format((float) $value, 0, ',', '.');
    $statusOptions = [
        'proposal' => ['draft', 'sent', 'approved', 'rejected'],
        'salary' => ['draft', 'approved', 'paid'],
        'reimbursement' => ['pending', 'approved', 'paid', 'rejected'],
        'invoice' => ['draft', 'sent', 'partial', 'paid', 'void'],
    ];
    $isPage = fn (...$pages) => in_array($activePage ?? 'dashboard', $pages, true);
    $icon = fn (string $name) => match ($name) {
        'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
        'list' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>',
        'projects' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18"/><path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><rect x="4" y="7" width="16" height="13" rx="2"/></svg>',
        'plus' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>',
        'proposal' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M8 13h8M8 17h5"/></svg>',
        'employees' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'salary' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 9v.01M18 15v.01"/></svg>',
        'reimbursement' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg>',
        'cashflow' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
        'invoice' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>',
        'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.6.2 1 .7 1 1.3H21a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15z"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'master' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5V4a2 2 0 0 1 2-2h12v20H6a2 2 0 0 1-2-2.5z"/><path d="M8 7h6M8 11h8M8 15h5"/></svg>',
        'trash' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>',
        'audit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
        'reports' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16v-5M12 16V7M17 16v-8"/></svg>',
        'backup' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>',
        default => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>',
    };
@endphp

@section('body')
<div class="app-shell">
    @include('erp.partials.sidebar')

    <div class="content">
        @include('erp.partials.navbar')

<main class="shell" id="dashboard">
    <header class="topbar">
        <div>
            <h1>{{ $pageTitle ?? 'Dashboard' }}</h1>
            <div class="muted">End-to-end ERP — sales, HR, finance, project costing, invoice, payment, report.</div>
        </div>
        @if($isPage('dashboard'))
        <div class="hidden items-center gap-2 md:flex">
            <span class="badge">{{ now()->format('d M Y') }}</span>
        </div>
        @endif
    </header>

    @include('erp.partials.alerts')

    {{-- ── Filter quick-stat cards ──────────────────────────────── --}}
    <section class="filter-chips section" id="filter-chips">
        <a href="{{ route('projects.index') }}"
           class="filter-chip {{ $isPage('projects') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('projects') !!}
            </div>
            <div class="filter-chip-count">{{ $filterCounts['projects_total'] }}</div>
            <div class="filter-chip-label">Projects</div>
            <div class="filter-chip-sub">{{ $filterCounts['projects_active'] }} aktif</div>
        </a>

        @if($can('admin', 'sales'))
        <a href="{{ route('sales.index') }}"
           class="filter-chip {{ $isPage('sales') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('proposal') !!}
            </div>
            <div class="filter-chip-count">{{ $filterCounts['proposals_pending'] }}</div>
            <div class="filter-chip-label">Proposals</div>
            <div class="filter-chip-sub">menunggu approval</div>
        </a>
        @endif

        @if($can('admin', 'finance'))
        <a href="{{ route('finance.index') }}"
           class="filter-chip {{ $isPage('invoices') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('invoice') !!}
            </div>
            <div class="filter-chip-count">{{ $filterCounts['invoices_outstanding'] }}</div>
            <div class="filter-chip-label">Invoices</div>
            <div class="filter-chip-sub">belum lunas</div>
        </a>
        @endif

        @if($can('admin', 'hr'))
        <a href="{{ route('hr.index') }}"
           class="filter-chip {{ $isPage('employees') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('employees') !!}
            </div>
            <div class="filter-chip-count">{{ $filterCounts['employees_total'] }}</div>
            <div class="filter-chip-label">Karyawan</div>
            <div class="filter-chip-sub">total terdaftar</div>
        </a>
        @endif

        @if($can('admin', 'finance', 'hr'))
        <a href="{{ route('reimbursements.index-page') }}"
           class="filter-chip {{ $isPage('reimbursements') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('reimbursement') !!}
            </div>
            <div class="filter-chip-count">{{ $filterCounts['reimbursements_pending'] }}</div>
            <div class="filter-chip-label">Reimburse</div>
            <div class="filter-chip-sub">pending approval</div>
        </a>
        @endif

        @if($can('admin', 'finance'))
        <a href="{{ route('cashflows.index-page') }}"
           class="filter-chip {{ $isPage('cashflows') ? 'active' : '' }}">
            <div class="filter-chip-icon">
                {!! $icon('cashflow') !!}
            </div>
            <div class="filter-chip-count">{{ $summary['balance'] >= 0 ? '+' : '' }}{{ number_format($summary['balance'] / 1000000, 1) }}M</div>
            <div class="filter-chip-label">Balance</div>
            <div class="filter-chip-sub">net cashflow</div>
        </a>
        @endif
    </section>

    {{-- ── Search & Filter panel ────────────────────────────────── --}}
    <form method="get" action="{{ url()->current() }}" class="filter-panel section">
        <div class="filter-panel-header">
            <div class="filter-panel-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M7 12h10M11 18h2"/></svg>
            </div>
            <h3>Search & Filter</h3>
            @if(request()->hasAny(['q','project_id','date_from','date_to','proposal_status','invoice_status']))
                <span class="badge ml-auto">Filter aktif</span>
            @endif
        </div>
        <div class="filter-grid">
            <div class="filter-field sm:col-span-2">
                <label>Pencarian</label>
                <input name="q" value="{{ request('q') }}" placeholder="Project, client, karyawan, cashflow…">
            </div>
            <div class="filter-field sm:col-span-2">
                <label>Project</label>
                <select name="project_id">
                    <option value="">Semua project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected($selectedProjectId === $project->id)>{{ $project->code }} — {{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label>Dari tanggal</label>
                <input name="date_from" type="date" value="{{ request('date_from') }}">
            </div>
            <div class="filter-field">
                <label>Sampai tanggal</label>
                <input name="date_to" type="date" value="{{ request('date_to') }}">
            </div>
            <div class="filter-field">
                <label>Status proposal</label>
                <select name="proposal_status">
                    <option value="">Semua</option>
                    @foreach($statusOptions['proposal'] as $status)
                        <option @selected(request('proposal_status')===$status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label>Status invoice</label>
                <select name="invoice_status">
                    <option value="">Semua</option>
                    @foreach($statusOptions['invoice'] as $status)
                        <option @selected(request('invoice_status')===$status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="filter-actions">
            <button>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18M7 12h10M11 18h2"/></svg>
                Terapkan Filter
            </button>
            <a class="button ghost" href="{{ url()->current() }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                Reset
            </a>
        </div>
    </form>

    {{-- ── Notifications ────────────────────────────────────────── --}}
    @if($isPage('dashboard') && $notifications->isNotEmpty())
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

    {{-- ── Dashboard KPI & Charts ───────────────────────────────── --}}
    @if($isPage('dashboard'))

    {{-- Row 1: 4 KPI Cards --}}
    <section class="grid cards section" id="summary">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
                </div>
                <span class="stat-card-badge up">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    Bulan ini
                </span>
            </div>
            <div class="stat-card-metric good">{{ $rp($dashboard['month_income']) }}</div>
            <div class="stat-card-label">Revenue Bulan Ini</div>
            <div class="stat-card-sub">
                <svg class="h-3.5 w-3.5 text-[#0059A7]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                {{ now()->format('F Y') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 7 6 6-4 4 8 8"/><path d="M3 3h7v7"/></svg>
                </div>
                <span class="stat-card-badge down">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                    Bulan ini
                </span>
            </div>
            <div class="stat-card-metric bad">{{ $rp($dashboard['month_expense']) }}</div>
            <div class="stat-card-label">Expense Bulan Ini</div>
            <div class="stat-card-sub">
                @php $burnRate = $dashboard['month_income'] > 0 ? round(($dashboard['month_expense'] / $dashboard['month_income']) * 100) : 0; @endphp
                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg>
                Burn rate {{ $burnRate }}% dari income
            </div>
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
            <div class="stat-card-sub">
                <svg class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                Belum terbayar / partial
            </div>
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
            <div class="stat-card-sub">
                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $filterCounts['employees_total'] }} karyawan aktif
            </div>
        </div>
    </section>

    {{-- Row 2: Cash summary --}}
    <section class="grid cards section" id="cash-summary">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="stat-card-metric good">{{ $rp($summary['income']) }}</div>
            <div class="stat-card-label">Total Income</div>
            <div class="stat-card-sub">Semua cashflow income</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="stat-card-metric bad">{{ $rp($summary['expense']) }}</div>
            <div class="stat-card-label">Total Expense</div>
            <div class="stat-card-sub">Semua cashflow expense</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon {{ $summary['balance'] >= 0 ? 'success' : 'danger' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-4"/></svg>
                </div>
                <span class="stat-card-badge {{ $summary['balance'] >= 0 ? 'up' : 'down' }}">
                    {{ $summary['balance'] >= 0 ? 'Surplus' : 'Defisit' }}
                </span>
            </div>
            <div class="stat-card-metric {{ $summary['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($summary['balance']) }}</div>
            <div class="stat-card-label">Net Cash Balance</div>
            <div class="stat-card-sub">Income − Expense</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon warn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg>
                </div>
                <span class="stat-card-badge neutral">{{ $filterCounts['reimbursements_pending'] }} pending</span>
            </div>
            <div class="stat-card-metric">{{ $rp($dashboard['pending_reimbursement']) }}</div>
            <div class="stat-card-label">Reimburse Pending</div>
            <div class="stat-card-sub">Menunggu approval</div>
        </div>
    </section>

    {{-- Row 3: Charts --}}
    <section class="grid two section">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Revenue vs Expense</div>
                    <div class="chart-sub">6 bulan terakhir berdasarkan cashflow</div>
                </div>
                <span class="badge">Monthly</span>
            </div>
            <div class="mt-6 flex h-64 items-end gap-3">
                @foreach($monthlyChart as $row)
                    @php
                        $incomeHeight  = max(4, ($row['income']  / $monthlyMax) * 100);
                        $expenseHeight = max(4, ($row['expense'] / $monthlyMax) * 100);
                    @endphp
                    <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                        <div class="flex h-52 w-full items-end justify-center gap-1.5 rounded-2xl bg-[#f3f8fc] px-2 py-2">
                            <div title="Income {{ $rp($row['income']) }}"
                                 class="w-5 rounded-t-lg bg-[#0059A7] transition-all"
                                 style="height: {{ $incomeHeight }}%"></div>
                            <div title="Expense {{ $rp($row['expense']) }}"
                                 class="w-5 rounded-t-lg bg-[#b42318] transition-all"
                                 style="height: {{ $expenseHeight }}%"></div>
                        </div>
                        <div class="text-[11px] font-black text-slate-500">{{ $row['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex gap-5 border-t border-[#d7e3ef] pt-4 text-sm font-bold">
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-[#0059A7]"></span> Income
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-[#b42318]"></span> Expense
                </span>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Expense Breakdown</div>
                    <div class="chart-sub">Komposisi biaya berdasarkan tipe</div>
                </div>
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

    {{-- Row 4: Project Cashflow bars --}}
    <section class="card section">
        <div class="section-head">
            <div>
                <h2>Project Cashflow</h2>
                <p class="muted">Perbandingan income dan expense per project (top 5)</p>
            </div>
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
                        <a href="{{ route('projects.show', $report['project']) }}" class="font-black text-[#002F59] hover:text-[#0059A7]">
                            {{ $report['project']->code }} — {{ $report['project']->name }}
                        </a>
                        <div class="muted mt-0.5">P/L: <span class="{{ $marginClass }} font-bold">{{ $rp($report['summary']['balance']) }}</span></div>
                    </div>
                    <span class="badge badge-{{ $report['profit_margin'] >= 0 ? 'approved' : 'rejected' }}">
                        {{ number_format($report['profit_margin'], 1) }}% margin
                    </span>
                </div>
                <div class="grid gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-20 text-xs font-black text-slate-500">Income</div>
                        <div class="h-3 flex-1 rounded-full bg-[#e8f2fb]">
                            <div class="h-full rounded-full bg-[#0059A7]" style="width: {{ $incomeWidth }}%"></div>
                        </div>
                        <div class="w-32 text-right text-xs font-black good">{{ $rp($report['summary']['income']) }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-20 text-xs font-black text-slate-500">Expense</div>
                        <div class="h-3 flex-1 rounded-full bg-[#e8f2fb]">
                            <div class="h-full rounded-full bg-[#b42318]" style="width: {{ $expenseWidth }}%"></div>
                        </div>
                        <div class="w-32 text-right text-xs font-black bad">{{ $rp($report['summary']['expense']) }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="mt-4 rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </section>
    @endif

    @if ($can('admin') && $isPage('company', 'users', 'masters'))
    <section class="grid three section" id="settings">
        @if($isPage('company'))
        <div class="card">
            <h2>Setting Perusahaan</h2>
            <form method="post" action="{{ route('company-setting.update') }}" enctype="multipart/form-data" class="grid">
                @csrf
                <input name="company_name" value="{{ $companySetting->company_name }}" required>
                <input name="email" type="email" value="{{ $companySetting->email }}" placeholder="Email">
                <input name="phone" value="{{ $companySetting->phone }}" placeholder="Phone">
                <input name="npwp" value="{{ $companySetting->npwp }}" placeholder="NPWP">
                <textarea name="address" placeholder="Alamat">{{ $companySetting->address }}</textarea>
                <input name="signature_name" value="{{ $companySetting->signature_name }}" placeholder="Nama tanda tangan">
                <select name="default_bank_account_id"><option value="">Bank default</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected($companySetting->default_bank_account_id===$bank->id)>{{ $bank->name }}</option>@endforeach</select>
                <input name="logo" type="file">
                <button>Simpan Setting</button>
            </form>
        </div>
        @endif
        @if($isPage('users'))
        <div class="card" id="users">
            <h2>User Management</h2>
            <form method="post" action="{{ route('users.store') }}" class="grid">
                @csrf
                <input name="name" placeholder="Nama" required>
                <input name="email" type="email" placeholder="Email" required>
                <select name="role"><option>admin</option><option>hr</option><option>finance</option><option>sales</option></select>
                <input name="password" placeholder="Password optional, min 8">
                <button>Tambah User</button>
            </form>
            <details><summary>User List</summary>
                <table>
                    @foreach($users as $user)
                    <tr><td>{{ $user->name }}<br><span class="muted">{{ $user->email }} | {{ $user->role }} | {{ $user->is_active ? 'active' : 'inactive' }}</span></td><td class="actions"><form method="post" action="{{ route('users.reset-password', $user) }}">@csrf @method('patch')<button class="mini ghost">Reset</button></form><form method="post" action="{{ route('users.destroy', $user) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>
                    @endforeach
                </table>
            </details>
        </div>
        @endif
        @if($isPage('masters'))
        <div class="card" id="master-data">
            <h2>Master Data</h2>
            <form method="post" action="{{ route('masters.store', 'clients') }}" class="grid">
                @csrf
                <h3>Client</h3>
                <input name="name" placeholder="Nama client" required>
                <input name="contact_name" placeholder="PIC">
                <input name="email" type="email" placeholder="Email">
                <button>Tambah Client</button>
            </form>
            <details><summary>Client List</summary>
                <table>@foreach($clients as $client)<tr><td>{{ $client->name }}<br><span class="muted">{{ $client->contact_name }}</span></td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['clients', $client->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>@endforeach</table>
            </details>
        </div>
        <div class="card">
            <h2>HR Master</h2>
            <form method="post" action="{{ route('masters.store', 'departments') }}" class="toolbar">@csrf<input name="name" placeholder="Department" required><button class="mini">Add</button></form>
            <form method="post" action="{{ route('masters.store', 'job_positions') }}" class="toolbar" style="margin-top:8px">@csrf<input name="name" placeholder="Job position" required><button class="mini">Add</button></form>
            <details><summary>Department & Position</summary>
                <table>
                    @foreach($departments as $department)<tr><td>Dept: {{ $department->name }}</td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['departments', $department->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>@endforeach
                    @foreach($jobPositions as $position)<tr><td>Pos: {{ $position->name }}</td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['job_positions', $position->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>@endforeach
                </table>
            </details>
        </div>
        <div class="card">
            <h2>Finance Master</h2>
            <form method="post" action="{{ route('masters.store', 'expense_categories') }}" class="toolbar">@csrf<input name="name" placeholder="Expense category" required><input name="type" placeholder="cloud/tools/vendor" required><button class="mini">Add</button></form>
            <form method="post" action="{{ route('masters.store', 'bank_accounts') }}" class="grid" style="margin-top:8px">@csrf<input name="name" placeholder="Nama kas/bank" required><input name="bank_name" placeholder="Bank"><input name="account_number" placeholder="No rekening"><input name="opening_balance" type="number" value="0"><button class="mini">Add Bank</button></form>
        </div>
        @endif
    </section>
    @endif

    @if($isPage('project-create', 'project-edit', 'proposal-create', 'proposal-edit', 'employee-create', 'employee-edit', 'salary-create', 'salary-edit', 'reimbursement-create', 'reimbursement-edit', 'cashflow-create', 'cashflow-edit', 'invoice-create', 'invoice-edit', 'payment-create'))
    <section class="grid two section" id="forms">
        @if ($can('admin', 'sales') && $isPage('project-edit') && isset($editingProject))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Project</h2>
                <p class="muted">Perbarui informasi project.</p>
            </div>
            <form method="post" action="{{ route('projects.update', $editingProject) }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Kode</label><input name="code" value="{{ $editingProject->code }}" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach(['planning','active','done','hold'] as $status)<option @selected($editingProject->status===$status)>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Nama Project</label><input name="name" value="{{ $editingProject->name }}" required></div>
                <div class="grid gap-1.5"><label>Client</label><select name="client_id"><option value="">Manual</option>@foreach ($clients as $client)<option value="{{ $client->id }}" @selected($editingProject->client_id===$client->id)>{{ $client->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Client Manual</label><input name="client" value="{{ $editingProject->client }}"></div>
                <div class="grid gap-1.5"><label>Budget</label><input name="budget" type="number" min="0" value="{{ $editingProject->budget }}"></div>
                <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="contract_value" type="number" min="0" value="{{ $editingProject->contract_value }}"></div>
                <div class="grid gap-1.5"><label>Mulai</label><input name="start_date" type="date" value="{{ $editingProject->start_date }}"></div>
                <div class="grid gap-1.5"><label>Selesai</label><input name="end_date" type="date" value="{{ $editingProject->end_date }}"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Upload Kontrak Baru</label><input name="contract_file" type="file"></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Project</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('projects.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'sales') && $isPage('project-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Project</h2>
                <p class="muted">Buat project baru untuk klien.</p>
            </div>
            <form method="post" action="{{ route('projects.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Kode</label><input name="code" placeholder="Auto jika kosong"></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status"><option>planning</option><option>active</option><option>done</option><option>hold</option></select></div>
                <div class="grid gap-1.5"><label>Nama Project</label><input name="name" required></div>
                <div class="grid gap-1.5"><label>Client</label><select name="client_id"><option value="">Manual</option>@foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Client Manual</label><input name="client"></div>
                <div class="grid gap-1.5"><label>Budget</label><input name="budget" type="number" min="0" value="0"></div>
                <div class="grid gap-1.5"><label>Nilai Kontrak</label><input name="contract_value" type="number" min="0" value="0"></div>
                <div class="grid gap-1.5"><label>Mulai</label><input name="start_date" type="date"></div>
                <div class="grid gap-1.5"><label>Selesai</label><input name="end_date" type="date"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Upload Kontrak</label><input name="contract_file" type="file"></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Project</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('projects.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'sales') && $isPage('proposal-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Proposal</h2>
                <p class="muted">Buat proposal baru untuk project.</p>
            </div>
            <form method="post" action="{{ route('proposals.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>No Proposal</label><input name="number" placeholder="Auto jika kosong"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Project</label><select name="project_id" required>@foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Judul</label><input name="title" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['proposal'] as $status)<option>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
                <div class="grid gap-1.5"><label>Valid Until</label><input name="valid_until" type="date"></div>
                <div class="grid gap-1.5"><label>Upload Signed</label><input name="signed_file" type="file"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Scope</label><textarea name="scope"></textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Proposal</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('sales.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'sales') && $isPage('proposal-edit') && isset($editingProposal))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Proposal</h2>
                <p class="muted">Perbarui informasi proposal.</p>
            </div>
            <form method="post" action="{{ route('proposals.update', $editingProposal) }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>No Proposal</label><input name="number" value="{{ $editingProposal->number }}"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Project</label><select name="project_id" required>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected($editingProposal->project_id===$project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Judul</label><input name="title" value="{{ $editingProposal->title }}" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['proposal'] as $status)<option @selected($editingProposal->status===$status)>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $editingProposal->amount }}" required></div>
                <div class="grid gap-1.5"><label>Valid Until</label><input name="valid_until" type="date" value="{{ $editingProposal->valid_until }}"></div>
                <div class="grid gap-1.5"><label>Upload Signed Baru</label><input name="signed_file" type="file"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Scope</label><textarea name="scope">{{ $editingProposal->scope }}</textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Proposal</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('sales.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr') && $isPage('employee-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Karyawan</h2>
                <p class="muted">Daftarkan karyawan baru.</p>
            </div>
            <form method="post" action="{{ route('employees.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Nama</label><input name="name" required></div>
                <div class="grid gap-1.5"><label>Posisi</label><select name="job_position_id"><option value="">Manual</option>@foreach($jobPositions as $position)<option value="{{ $position->id }}">{{ $position->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Posisi Manual</label><input name="position"></div>
                <div class="grid gap-1.5"><label>Department</label><select name="department_id"><option value="">Manual</option>@foreach($departments as $department)<option value="{{ $department->id }}">{{ $department->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Department Manual</label><input name="department" value="IT"></div>
                <div class="grid gap-1.5"><label>Base Salary</label><input name="base_salary" type="number" min="0" required></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Karyawan</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('hr.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr') && $isPage('employee-edit') && isset($editingEmployee))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Karyawan</h2>
                <p class="muted">Perbarui data karyawan.</p>
            </div>
            <form method="post" action="{{ route('employees.update', $editingEmployee) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Nama</label><input name="name" value="{{ $editingEmployee->name }}" required></div>
                <div class="grid gap-1.5"><label>Posisi</label><select name="job_position_id"><option value="">Manual</option>@foreach($jobPositions as $position)<option value="{{ $position->id }}" @selected($editingEmployee->job_position_id===$position->id)>{{ $position->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Posisi Manual</label><input name="position" value="{{ $editingEmployee->position }}"></div>
                <div class="grid gap-1.5"><label>Department</label><select name="department_id"><option value="">Manual</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected($editingEmployee->department_id===$department->id)>{{ $department->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Department Manual</label><input name="department" value="{{ $editingEmployee->department }}"></div>
                <div class="grid gap-1.5"><label>Base Salary</label><input name="base_salary" type="number" min="0" value="{{ $editingEmployee->base_salary }}" required></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Karyawan</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('hr.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr') && $isPage('salary-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Salary</h2>
                <p class="muted">Catat gaji karyawan.</p>
            </div>
            <form method="post" action="{{ route('salaries.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Karyawan</label><select name="employee_id" required>@foreach ($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Non Project</option>@foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Period</label><input name="period" placeholder="{{ now()->format('Y-m') }}" required></div>
                <div class="grid gap-1.5"><label>Base</label><input name="base_salary" type="number" min="0" required></div>
                <div class="grid gap-1.5"><label>Allowance</label><input name="allowance" type="number" min="0" value="0"></div>
                <div class="grid gap-1.5"><label>Deduction</label><input name="deduction" type="number" min="0" value="0"></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Salary</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('salaries.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr') && $isPage('salary-edit') && isset($editingSalary))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Salary</h2>
                <p class="muted">Perbarui data gaji karyawan.</p>
            </div>
            <form method="post" action="{{ route('salaries.update', $editingSalary) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Karyawan</label><select name="employee_id" required>@foreach ($employees as $employee)<option value="{{ $employee->id }}" @selected($editingSalary->employee_id===$employee->id)>{{ $employee->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Non Project</option>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected($editingSalary->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Period</label><input name="period" value="{{ $editingSalary->period }}" required></div>
                <div class="grid gap-1.5"><label>Base</label><input name="base_salary" type="number" min="0" value="{{ $editingSalary->base_salary }}" required></div>
                <div class="grid gap-1.5"><label>Allowance</label><input name="allowance" type="number" min="0" value="{{ $editingSalary->allowance }}"></div>
                <div class="grid gap-1.5"><label>Deduction</label><input name="deduction" type="number" min="0" value="{{ $editingSalary->deduction }}"></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Salary</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('salaries.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr', 'finance') && $isPage('reimbursement-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Reimbursement</h2>
                <p class="muted">Catat pengajuan reimbursement.</p>
            </div>
            <form method="post" action="{{ route('reimbursements.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Karyawan</label><select name="employee_id" required>@foreach ($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Non Project</option>@foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Kategori</label><input name="category" placeholder="Transport / Meals" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['reimbursement'] as $status)<option>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
                <div class="grid gap-1.5"><label>Tanggal</label><input name="expense_date" type="date" required></div>
                <div class="grid gap-1.5"><label>Bukti/Receipt</label><input name="receipt_file" type="file"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Deskripsi</label><textarea name="description"></textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Reimbursement</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('reimbursements.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'hr', 'finance') && $isPage('reimbursement-edit') && isset($editingReimbursement))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Reimbursement</h2>
                <p class="muted">Perbarui data reimbursement.</p>
            </div>
            <form method="post" action="{{ route('reimbursements.update', $editingReimbursement) }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Karyawan</label><select name="employee_id" required>@foreach ($employees as $employee)<option value="{{ $employee->id }}" @selected($editingReimbursement->employee_id===$employee->id)>{{ $employee->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Non Project</option>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected($editingReimbursement->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Kategori</label><input name="category" value="{{ $editingReimbursement->category }}" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['reimbursement'] as $status)<option @selected($editingReimbursement->status===$status)>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $editingReimbursement->amount }}" required></div>
                <div class="grid gap-1.5"><label>Tanggal</label><input name="expense_date" type="date" value="{{ $editingReimbursement->expense_date }}" required></div>
                <div class="grid gap-1.5"><label>Bukti Baru</label><input name="receipt_file" type="file"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Deskripsi</label><textarea name="description">{{ $editingReimbursement->description }}</textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Reimbursement</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('reimbursements.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'finance') && $isPage('cashflow-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Tambah Cashflow</h2>
                <p class="muted">Catat pemasukan atau pengeluaran.</p>
            </div>
            <form method="post" action="{{ route('cashflows.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Company</option>@foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Type</label><select name="type"><option value="income">Income</option><option value="expense">Expense</option></select></div>
                <div class="grid gap-1.5"><label>Cost Type</label><select name="cost_type"><option>operational</option><option>tools</option><option>cloud</option><option>vendor</option><option>subcontractor</option><option>client_payment</option></select></div>
                <div class="grid gap-1.5"><label>Category</label><input name="category" required></div>
                <div class="grid gap-1.5"><label>Expense Category</label><select name="expense_category_id"><option value="">-</option>@foreach($expenseCategories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Bank/Kas</label><select name="bank_account_id"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Vendor</label><input name="vendor"></div>
                <div class="grid gap-1.5"><label>Tanggal</label><input name="transaction_date" type="date" required></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
                <div class="grid gap-1.5"><label>Description</label><input name="description" required></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Cashflow</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('cashflows.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'finance') && $isPage('cashflow-edit') && isset($editingCashflow))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Cashflow</h2>
                <p class="muted">Perbarui data cashflow.</p>
            </div>
            <form method="post" action="{{ route('cashflows.update', $editingCashflow) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Project</label><select name="project_id"><option value="">Company</option>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected($editingCashflow->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Type</label><select name="type"><option value="income" @selected($editingCashflow->type==='income')>Income</option><option value="expense" @selected($editingCashflow->type==='expense')>Expense</option></select></div>
                <div class="grid gap-1.5"><label>Cost Type</label><select name="cost_type">@foreach(['operational','salary','reimbursement','tools','cloud','vendor','subcontractor','client_payment'] as $costType)<option @selected($editingCashflow->cost_type===$costType)>{{ $costType }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Category</label><input name="category" value="{{ $editingCashflow->category }}" required></div>
                <div class="grid gap-1.5"><label>Expense Category</label><select name="expense_category_id"><option value="">-</option>@foreach($expenseCategories as $category)<option value="{{ $category->id }}" @selected($editingCashflow->expense_category_id===$category->id)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Bank/Kas</label><select name="bank_account_id"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected($editingCashflow->bank_account_id===$bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Vendor</label><input name="vendor" value="{{ $editingCashflow->vendor }}"></div>
                <div class="grid gap-1.5"><label>Tanggal</label><input name="transaction_date" type="date" value="{{ $editingCashflow->transaction_date }}" required></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $editingCashflow->amount }}" required></div>
                <div class="grid gap-1.5"><label>Description</label><input name="description" value="{{ $editingCashflow->description }}" required></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Cashflow</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('cashflows.index-page') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'finance') && $isPage('invoice-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Buat Invoice</h2>
                <p class="muted">Buat invoice baru untuk project.</p>
            </div>
            <form method="post" action="{{ route('invoices.store') }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Project</label><select name="project_id" required>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Proposal Approved</label><select name="proposal_id"><option value="">Tanpa proposal</option>@foreach($proposals->where('status','approved') as $proposal)<option value="{{ $proposal->id }}">{{ $proposal->title }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>No Invoice</label><input name="number" placeholder="Auto jika kosong"></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['invoice'] as $status)<option>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Issue</label><input name="issue_date" type="date" value="{{ now()->toDateString() }}" required></div>
                <div class="grid gap-1.5"><label>Due</label><input name="due_date" type="date"></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" required></div>
                <div class="grid gap-1.5"><label>Tax %</label><input name="tax_rate" type="number" min="0" value="0"></div>
                <div class="grid gap-1.5"><label>Notes</label><input name="notes"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Payment Terms</label><textarea name="payment_terms"></textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Invoice</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('finance.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'finance') && $isPage('payment-create'))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Catat Payment</h2>
                <p class="muted">Catat pembayaran dari invoice.</p>
            </div>
            <form method="post" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf
                <div class="grid gap-1.5"><label>Invoice</label><select name="invoice_id" required>@foreach($invoices->where('status','!=','paid') as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->number }} - sisa {{ $rp($invoice->amount - $invoice->paid_amount) }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Bank/Kas</label><select name="bank_account_id"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}">{{ $bank->name }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="1" required></div>
                <div class="grid gap-1.5"><label>Tanggal</label><input name="payment_date" type="date" value="{{ now()->toDateString() }}" required></div>
                <div class="grid gap-1.5"><label>Method</label><input name="method" value="transfer" required></div>
                <div class="grid gap-1.5"><label>Reference</label><input name="reference" placeholder="Auto jika kosong"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Upload Bukti Transfer</label><input name="proof_file" type="file"></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Simpan Payment</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('finance.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif

        @if ($can('admin', 'finance') && $isPage('invoice-edit') && isset($editingInvoice))
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-8 shadow-lg shadow-[#002F59]/5">
            <div class="mb-8 border-b border-[#d7e3ef] pb-5">
                <h2 class="mb-1">Edit Invoice</h2>
                <p class="muted">Perbarui data invoice.</p>
            </div>
            <form method="post" action="{{ route('invoices.update', $editingInvoice) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                @csrf @method('put')
                <div class="grid gap-1.5"><label>Project</label><select name="project_id" required>@foreach($projects as $project)<option value="{{ $project->id }}" @selected($editingInvoice->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Proposal Approved</label><select name="proposal_id"><option value="">Tanpa proposal</option>@foreach($proposals->where('status','approved') as $proposal)<option value="{{ $proposal->id }}" @selected($editingInvoice->proposal_id===$proposal->id)>{{ $proposal->title }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>No Invoice</label><input name="number" value="{{ $editingInvoice->number }}" required></div>
                <div class="grid gap-1.5"><label>Status</label><select name="status">@foreach($statusOptions['invoice'] as $status)<option @selected($editingInvoice->status===$status)>{{ $status }}</option>@endforeach</select></div>
                <div class="grid gap-1.5"><label>Issue</label><input name="issue_date" type="date" value="{{ $editingInvoice->issue_date }}" required></div>
                <div class="grid gap-1.5"><label>Due</label><input name="due_date" type="date" value="{{ $editingInvoice->due_date }}"></div>
                <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $editingInvoice->amount }}" required></div>
                <div class="grid gap-1.5"><label>Tax %</label><input name="tax_rate" type="number" min="0" value="{{ $editingInvoice->tax_rate }}"></div>
                <div class="grid gap-1.5"><label>Notes</label><input name="notes" value="{{ $editingInvoice->notes }}"></div>
                <div class="grid gap-1.5 md:col-span-2"><label>Payment Terms</label><textarea name="payment_terms">{{ $editingInvoice->payment_terms }}</textarea></div>
                <div class="mt-4 flex items-center gap-3 md:col-span-2">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Invoice</button>
                    <a class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#e8f2fb] px-5 py-2.5 text-sm font-black text-[#0059A7] shadow-none transition hover:bg-[#d7e3ef] hover:text-[#002F59]" href="{{ route('finance.index') }}"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali</a>
                </div>
            </form>
        </div>
        @endif
    </section>
    @endif

    @if($isPage('projects', 'reports'))
    <section class="section" id="project-finance">
        <div class="section-head">
            <h2>Project Finance Detail</h2>
            <span class="badge">{{ $projectReports->count() }} project</span>
        </div>
        <div class="grid three">
            @forelse ($projectReports as $report)
                <div class="card">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h3 class="mb-0.5">
                                <a href="{{ route('projects.show', $report['project']) }}" class="hover:text-[#0059A7]">
                                    {{ $report['project']->code }} — {{ $report['project']->name }}
                                </a>
                            </h3>
                            <div class="muted">{{ $report['project']->client }}</div>
                        </div>
                        <span class="badge badge-{{ $report['project']->status }}">{{ $report['project']->status }}</span>
                    </div>
                    <table>
                        <tr><td class="text-slate-500">Kontrak</td><td class="text-right font-bold">{{ $rp($report['project']->contract_value) }}</td></tr>
                        <tr><td class="text-slate-500">Income</td><td class="good text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                        <tr><td class="text-slate-500">Expense</td><td class="bad text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                        <tr><td class="text-slate-500">Profit/Loss</td><td class="{{ $report['summary']['balance'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                        <tr><td class="text-slate-500">Margin</td><td class="text-right font-bold">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                        <tr><td class="text-slate-500">Salary</td><td class="text-right">{{ $rp($report['salary_total']) }}</td></tr>
                        <tr><td class="text-slate-500">Reimburse</td><td class="text-right">{{ $rp($report['reimbursement_total']) }}</td></tr>
                        <tr><td class="text-slate-500">Invoice</td><td class="text-right">{{ $rp($report['invoice_total']) }}</td></tr>
                    </table>
                </div>
            @empty
                <div class="col-span-full rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
            @endforelse
        </div>
    </section>
    @endif

    @if($isPage('employees'))
    <section class="card section wide">
        <div class="section-head">
            <h2>Employee List</h2>
            <span class="badge">{{ $employeesPage->total() }} total</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Department</th>
                    <th>Base Salary</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employeesPage as $employee)
                    <tr>
                        <td class="font-bold">{{ $employee->name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->department }}</td>
                        <td class="font-bold">{{ $rp($employee->base_salary) }}</td>
                        <td class="actions">
                            <a class="button mini ghost" href="{{ route('employees.edit-page', $employee) }}">Edit</a>
                            @if($can('admin'))
                                <form method="post" action="{{ route('employees.destroy', $employee) }}">
                                    @csrf @method('delete')
                                    <button class="mini danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada karyawan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($employeesPage->hasPages())
            <div class="pager">
                <div class="text-sm text-slate-500">
                    Menampilkan {{ $employeesPage->firstItem() }}–{{ $employeesPage->lastItem() }} dari {{ $employeesPage->total() }}
                </div>
                {{ $employeesPage->links() }}
            </div>
        @endif
    </section>
    @endif

    @if($isPage('projects', 'sales'))
    <section class="grid two section" id="crud">
        @if($isPage('projects'))
        <div class="card wide">
            <div class="section-head">
                <h2>Daftar Projects</h2>
                <a class="button ghost" href="{{ route('projects.create-page') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah
                </a>
            </div>
            <table>
                <thead><tr><th>Project</th><th>Status</th><th>Kontrak / Budget</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($projectsPage as $project)
                        <tr>
                            <td>
                                <div class="font-black">{{ $project->code }}</div>
                                <div class="muted">{{ $project->name }} · {{ $project->client }}</div>
                            </td>
                            <td><span class="badge badge-{{ $project->status }}">{{ $project->status }}</span></td>
                            <td>
                                <div class="font-bold">{{ $rp($project->contract_value) }}</div>
                                <div class="muted">Budget {{ $rp($project->budget) }}</div>
                            </td>
                            <td class="actions">
                                <a class="button mini ghost" href="{{ route('projects.show', $project) }}">Detail</a>
                                <a class="button mini ghost" href="{{ route('projects.edit-page', $project) }}">Edit</a>
                                @if($can('admin'))<form method="post" action="{{ route('projects.destroy', $project) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada project.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($projectsPage->hasPages())
                <div class="pager">
                    <div class="text-sm text-slate-500">{{ $projectsPage->firstItem() }}–{{ $projectsPage->lastItem() }} dari {{ $projectsPage->total() }}</div>
                    {{ $projectsPage->links() }}
                </div>
            @endif
        </div>
        @endif

        @if($isPage('sales'))
        <div class="card wide">
            <div class="section-head">
                <h2>Proposal Workflow</h2>
                <a class="button ghost" href="{{ route('proposals.create-page') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah
                </a>
            </div>
            <table>
                <thead><tr><th>Proposal</th><th>Status</th><th>Amount</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($proposalsPage as $proposal)
                        <tr>
                            <td>
                                <div class="font-bold">{{ $proposal->title }}</div>
                                <div class="muted">{{ $proposal->project->code }}</div>
                            </td>
                            <td>
                                <form method="post" action="{{ route('proposals.status', $proposal) }}" class="toolbar">
                                    @csrf @method('patch')
                                    <select name="status" class="w-auto min-h-9 py-1.5 text-xs">
                                        @foreach($statusOptions['proposal'] as $status)<option @selected($proposal->status===$status)>{{ $status }}</option>@endforeach
                                    </select>
                                    <button class="mini">Simpan</button>
                                </form>
                            </td>
                            <td class="font-bold">{{ $rp($proposal->amount) }}</td>
                            <td class="actions">
                                <a class="button mini ghost" href="{{ route('proposals.edit-page', $proposal) }}">Edit</a>
                                <a class="button mini ghost" href="{{ route('proposals.pdf', $proposal) }}" target="_blank">PDF</a>
                                @if($can('admin'))<form method="post" action="{{ route('proposals.destroy', $proposal) }}">@csrf @method('delete')<button class="mini danger">Hapus</button></form>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-500">Belum ada proposal.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($proposalsPage->hasPages())
                <div class="pager">
                    <div class="text-sm text-slate-500">{{ $proposalsPage->firstItem() }}–{{ $proposalsPage->lastItem() }} dari {{ $proposalsPage->total() }}</div>
                    {{ $proposalsPage->links() }}
                </div>
            @endif
        </div>
        @endif
    </section>
    @endif

    @if($isPage('salaries', 'reimbursements'))
    <section class="grid two section" id="workflow">
        @if($isPage('salaries'))
        <div class="card wide">
            <h2>Salary Workflow</h2>
            <table><thead><tr><th>Employee</th><th>Period</th><th>Status</th><th>Net</th><th>Aksi</th></tr></thead><tbody>
                @foreach($salaries as $salary)
                <tr><td>{{ $salary->employee->name }}<br><span class="muted">{{ $salary->project?->code ?? 'Non Project' }}</span></td><td>{{ $salary->period }}</td><td><form method="post" action="{{ route('salaries.status', $salary) }}" class="toolbar">@csrf @method('patch')<select name="status">@foreach($statusOptions['salary'] as $status)<option @selected($salary->status===$status)>{{ $status }}</option>@endforeach</select><button class="mini">Save</button></form></td><td>{{ $rp($salary->net_salary) }}</td><td class="actions"><a class="button mini ghost" href="{{ route('salaries.edit-page', $salary) }}">Edit</a><a class="button mini ghost" href="{{ route('salaries.pdf', $salary) }}" target="_blank">Slip</a>@if($can('admin'))<form method="post" action="{{ route('salaries.destroy', $salary) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>@endif</td></tr>
                @endforeach
            </tbody></table>
        </div>
        @endif

        @if($isPage('reimbursements'))
        <div class="card wide">
            <h2>Reimbursement Workflow</h2>
            <table><thead><tr><th>Employee</th><th>Category</th><th>Status</th><th>Amount</th><th>Aksi</th></tr></thead><tbody>
                @foreach($reimbursements as $reimbursement)
                <tr><td>{{ $reimbursement->employee->name }}<br><span class="muted">{{ $reimbursement->project?->code ?? 'Non Project' }}</span></td><td>{{ $reimbursement->category }}</td><td><form method="post" action="{{ route('reimbursements.status', $reimbursement) }}" class="toolbar">@csrf @method('patch')<select name="status">@foreach($statusOptions['reimbursement'] as $status)<option @selected($reimbursement->status===$status)>{{ $status }}</option>@endforeach</select><button class="mini">Save</button></form></td><td>{{ $rp($reimbursement->amount) }}</td><td class="actions"><a class="button mini ghost" href="{{ route('reimbursements.edit-page', $reimbursement) }}">Edit</a>@if($can('admin'))<form method="post" action="{{ route('reimbursements.destroy', $reimbursement) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>@endif</td></tr>
                @endforeach
            </tbody></table>
        </div>
        @endif
    </section>
    @endif

    @if($isPage('invoices', 'cashflows'))
    <section class="grid two section" id="invoice-payment">
        @if($isPage('invoices'))
        <div class="card wide">
            <h2>Invoice & Payment Detail</h2>
            <table><thead><tr><th>Invoice</th><th>Status</th><th>Paid</th><th>Aksi</th></tr></thead><tbody>
                @foreach($invoices as $invoice)
                <tr><td>{{ $invoice->number }}<br><span class="muted">{{ $invoice->project->code }} | due {{ $invoice->due_date }}</span></td><td><span class="badge">{{ $invoice->status }}</span></td><td>{{ $rp($invoice->paid_amount) }} / {{ $rp($invoice->amount) }}</td><td class="actions"><a class="button mini ghost" href="{{ route('invoices.edit-page', $invoice) }}">Edit</a><a class="button mini ghost" href="{{ route('invoices.pdf', $invoice) }}" target="_blank">PDF</a>@if($can('admin'))<form method="post" action="{{ route('invoices.destroy', $invoice) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>@endif</td></tr>
                @endforeach
            </tbody></table>
            <h3 style="margin-top:16px">Payments</h3>
            <table>@foreach($payments as $payment)<tr><td>{{ $payment->payment_date }}<br><span class="muted">{{ $payment->invoice->number }} | {{ $payment->bankAccount?->name }}</span></td><td>{{ $rp($payment->amount) }}</td><td>@if($can('admin'))<form method="post" action="{{ route('payments.destroy', $payment) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>@endif</td></tr>@endforeach</table>
            {{ $payments->links() }}
        </div>
        @endif

        @if($isPage('cashflows'))
        <div class="card wide">
            <h2>Cashflow Detail</h2>
            <table><thead><tr><th>Tanggal</th><th>Project</th><th>Type</th><th>Category</th><th>Amount</th><th>Aksi</th></tr></thead><tbody>
                @foreach ($cashflowPages as $flow)
                <tr><td>{{ $flow->transaction_date }}</td><td>{{ $flow->project?->code ?? 'Company' }}</td><td><span class="badge">{{ $flow->type }}</span></td><td>{{ $flow->category }}<br><span class="muted">{{ $flow->cost_type }} {{ $flow->vendor ? '| '.$flow->vendor : '' }}</span></td><td class="{{ $flow->type === 'income' ? 'good' : 'bad' }}">{{ $rp($flow->amount) }}</td><td class="actions"><a class="button mini ghost" href="{{ route('cashflows.edit-page', $flow) }}">Edit</a>@if($can('admin'))<form method="post" action="{{ route('cashflows.destroy', $flow) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>@endif</td></tr>
                @endforeach
            </tbody></table>
            {{ $cashflowPages->links() }}
        </div>
        @endif
    </section>
    @endif

    @if($can('admin') && $isPage('trash'))
    <section class="card section wide" id="trash">
        <h2>Trash / Restore</h2>
        <table>
            @foreach($trash as $type => $items)
                @foreach($items as $item)
                    <tr><td>{{ $type }}</td><td>{{ $item->name ?? $item->title ?? $item->number ?? $item->code }}</td><td><form method="post" action="{{ route('trash.restore', [$type, $item->id]) }}">@csrf @method('patch')<button class="mini ghost">Restore</button></form></td></tr>
                @endforeach
            @endforeach
        </table>
    </section>
    @endif
    @if($can('admin') && $isPage('audit'))
    <section class="card section wide" id="audit">
        <h2>Audit Log</h2>
        <table><thead><tr><th>Waktu</th><th>User</th><th>Action</th><th>Data</th><th>Deskripsi</th></tr></thead><tbody>
            @foreach($auditLogs as $log)
                <tr><td>{{ $log->created_at }}</td><td>{{ $log->user?->name ?? 'System' }}</td><td>{{ $log->action }}</td><td>{{ $log->auditable_type }} #{{ $log->auditable_id }}</td><td>{{ $log->description }}</td></tr>
            @endforeach
        </tbody></table>
        {{ $auditLogs->links() }}
    </section>
    @endif
</main>
    </div>
</div>
@endsection
