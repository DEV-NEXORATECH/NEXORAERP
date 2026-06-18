@php
    $activePage = $activePage ?? 'dashboard';
    $userRole   = auth()->user()->role;

    $pageIconKey = match(true) {
        in_array($activePage, ['projects','project-create','project-edit'])                    => 'projects',
        in_array($activePage, ['sales','proposal-create','proposal-edit'])                      => 'proposal',
        in_array($activePage, ['employees','employee-create','employee-edit'])                  => 'employees',
        in_array($activePage, ['salaries','salary-create','salary-edit'])                       => 'salary',
        in_array($activePage, ['reimbursements','reimbursement-create','reimbursement-edit'])   => 'reimbursement',
        in_array($activePage, ['cashflows','cashflow-create','cashflow-edit'])                  => 'cashflow',
        in_array($activePage, ['invoices','invoice-create','invoice-edit','payment-create'])    => 'invoice',
        $activePage === 'reports'  => 'reports',
        $activePage === 'company'  => 'settings',
        $activePage === 'users'    => 'users',
        $activePage === 'masters'  => 'master',
        $activePage === 'trash'    => 'trash',
        $activePage === 'audit'    => 'audit',
        default                    => 'dashboard',
    };

    $pageSection = match(true) {
        in_array($activePage, ['projects','project-create','project-edit','sales','proposal-create','proposal-edit']) => 'Sales',
        in_array($activePage, ['employees','employee-create','employee-edit','salaries','salary-create','salary-edit']) => 'HR',
        in_array($activePage, ['reimbursements','reimbursement-create','reimbursement-edit','cashflows','cashflow-create','cashflow-edit','invoices','invoice-create','invoice-edit','payment-create']) => 'Finance',
        $activePage === 'reports' => 'Reports',
        in_array($activePage, ['company','users','masters','trash','audit']) => 'Admin',
        default => 'Main',
    };

    $quickActions = [
        'projects'        => ['label' => 'New Project',   'route' => 'projects.create-page',       'can' => ['admin','sales']],
        'sales'           => ['label' => 'New Proposal',  'route' => 'proposals.create-page',      'can' => ['admin','sales']],
        'employees'       => ['label' => 'New Employee',  'route' => 'employees.create-page',      'can' => ['admin','hr']],
        'salaries'        => ['label' => 'New Salary',    'route' => 'salaries.create-page',       'can' => ['admin','hr']],
        'reimbursements'  => ['label' => 'New Reimburse', 'route' => 'reimbursements.create-page', 'can' => ['admin','hr','finance']],
        'cashflows'       => ['label' => 'New Cashflow',  'route' => 'cashflows.create-page',      'can' => ['admin','finance']],
        'invoices'        => ['label' => 'New Invoice',   'route' => 'invoices.create-page',       'can' => ['admin','finance']],
    ];

    $qa         = $quickActions[$activePage] ?? null;
    $showAction = $qa && ($userRole === 'admin' || in_array($userRole, $qa['can']));
    $notifCount = \App\Models\Reimbursement::where('status', 'pending')->count()
        + \App\Models\Invoice::whereNotIn('status', ['paid', 'void'])->whereDate('due_date', '<=', now()->addDays(7))->count()
        + \App\Models\Salary::where('status', 'approved')->count()
        + \App\Models\Proposal::where('status', 'sent')->count();

    $pageIcons = [
        'dashboard'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
        'projects'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18"/><path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><rect x="4" y="7" width="16" height="13" rx="2"/></svg>',
        'proposal'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M8 13h8M8 17h5"/></svg>',
        'employees'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'salary'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 9v.01M18 15v.01"/></svg>',
        'reimbursement' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg>',
        'cashflow'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
        'invoice'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>',
        'reports'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16v-5M12 16V7M17 16v-8"/></svg>',
        'settings'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.6.2 1 .7 1 1.3H21a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15z"/></svg>',
        'users'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'master'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5V4a2 2 0 0 1 2-2h12v20H6a2 2 0 0 1-2-2.5z"/><path d="M8 7h6M8 11h8M8 15h5"/></svg>',
        'trash'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>',
        'audit'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
    ];

    $pageIconSvg = $pageIcons[$pageIconKey] ?? $pageIcons['dashboard'];
@endphp

<header class="app-header">
    <div class="app-header-card">

        {{-- ── LEFT: Toggle + Context ──────────────────────────────────── --}}
        <div class="app-header-left">
            <button type="button" class="hdr-toggle" data-sidebar-toggle title="Toggle sidebar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="hdr-sep"></div>

            <div class="hdr-context">
                <div class="hdr-page-icon">
                    {!! $pageIconSvg !!}
                </div>
                <div class="hdr-page-meta">
                    <div class="hdr-page-title">{{ $pageTitle ?? 'Dashboard' }}</div>
                    <div class="hdr-page-path hidden sm:block">NEXORA ERP <span class="opacity-40">›</span> {{ $pageSection }}</div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Date · Action · Notifications · User ──────────────── --}}
        <div class="app-header-right">

            {{-- Date chip --}}
            <div class="hdr-date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <span>{{ now()->format('d M Y') }}</span>
            </div>

            {{-- Contextual quick-action button --}}
            @if($showAction)
            <a href="{{ route($qa['route']) }}" class="hdr-action-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span class="hdr-action-label">{{ $qa['label'] }}</span>
            </a>
            @endif

            {{-- Notification bell --}}
            <button type="button" class="hdr-icon-btn" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($notifCount > 0)
                <span class="hdr-notif-badge">{{ $notifCount }}</span>
                @endif
            </button>

            {{-- User chip --}}
            <div class="hdr-user-chip">
                <div class="hdr-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="hdr-user-meta">
                    <span class="hdr-user-name">{{ auth()->user()->name }}</span>
                    <span class="hdr-user-role">{{ strtoupper($userRole) }}</span>
                </div>
                <form method="post" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="hdr-logout-btn" title="Logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>
