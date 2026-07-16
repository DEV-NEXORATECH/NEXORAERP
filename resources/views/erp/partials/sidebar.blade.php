@php
    $modulePages = [
        'modules',
        'projects', 'project-create', 'project-edit',
        'sales', 'sales-crm', 'sales-inquiries', 'sales-leads', 'sales-orders', 'sales-targets', 'sales-commissions', 'client-contracts',
        'proposal-create', 'proposal-edit',
        'employees', 'hris', 'employee-create', 'employee-edit',
        'employee-skills', 'attendances', 'timesheets', 'leave-requests', 'performance-reviews', 'payroll-benefits',
        'salaries', 'salary-create', 'salary-edit',
        'reimbursements', 'reimbursement-create', 'reimbursement-edit',
        'cashflows', 'cashflow-create', 'cashflow-edit',
        'invoices', 'invoice-create', 'invoice-edit', 'payment-create',
        'finance-suite', 'finance-advanced',
        'chart-accounts', 'journal-entries', 'recurring-billings', 'payment-reminders', 'vendor-bills', 'vendor-payments',
        'budgets', 'tax-rules', 'fixed-assets', 'currency-rates', 'currency-variances', 'revenue-schedules',
        'bank-reconciliation-items', 'purchase-matches',
        'vendors', 'purchase-requisitions', 'purchase-orders', 'goods-receipts', 'procurement-contracts', 'procurement',
    ];

    $reportPages = [
        'reports',
        'reports-profit-loss',
        'reports-balance-sheet',
        'reports-cash-flow',
        'reports-project',
        'reports-aging',
        'reports-tax',
        'reports-budget',
        'reports-transactions',
        'reports-reconciliation',
    ];

    $settingsPages = [
        'cms',
        'company-settings',
        'user-management',
        'user-management.create-page',
        'user-management.edit-page',
        'clients',
        'departments',
        'job-positions',
        'expense-categories',
        'bank-accounts',
        'audit-logs',
        'trash',
    ];

    $onReportsHub = request()->routeIs('modules.show') && request()->route('section') === 'reports';
    $onAdminHub = request()->routeIs('modules.show') && request()->route('section') === 'admin';
    $onModuleHub = request()->routeIs('modules.index') || (request()->routeIs('modules.show') && ! in_array(request()->route('section'), ['reports', 'admin'], true));
@endphp

<aside class="sidebar">
    <div class="sidebar-head">
        <div class="sidebar-brand">
            <div class="logo">NX</div>
            <div class="min-w-0 flex-1">
                <div class="sidebar-title">NEXORA ERP</div>
                <div class="sidebar-subtitle">{{ strtoupper($role) }} workspace</div>
            </div>
        </div>
    </div>

    <div class="sidebar-scroll">
        <nav class="side-nav">
            <a class="{{ $isPage('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="side-icon">{!! $icon('dashboard') !!}</span>
                <span class="side-label">Dashboard</span>
            </a>

            <a class="{{ ($onModuleHub || $isPage(...$modulePages)) && ! $onReportsHub && ! $onAdminHub && ! $isPage(...$reportPages) && ! $isPage(...$settingsPages) ? 'active' : '' }}" href="{{ route('modules.index') }}">
                <span class="side-icon">{!! $icon('list') !!}</span>
                <span class="side-label">Module</span>
            </a>

            <a class="{{ $isPage('approvals') ? 'active' : '' }}" href="{{ route('approvals.index') }}">
                <span class="side-icon">{!! $icon('audit') !!}</span>
                <span class="side-label">Approval & Task</span>
            </a>

            @if($can('admin', 'finance'))
                <a class="{{ $onReportsHub || $isPage(...$reportPages) ? 'active' : '' }}" href="{{ route('modules.show', 'reports') }}">
                    <span class="side-icon">{!! $icon('reports') !!}</span>
                    <span class="side-label">Report & Analyst</span>
                </a>
            @endif

            @if($can('admin'))
                <a class="{{ $isPage('cms') ? 'active' : '' }}" href="{{ route('cms.index') }}">
                    <span class="side-icon">{!! $icon('list') !!}</span>
                    <span class="side-label">CMS</span>
                </a>

                <a class="{{ $onAdminHub || $isPage(...$settingsPages) ? 'active' : '' }}" href="{{ route('admin.index') }}">
                    <span class="side-icon">{!! $icon('settings') !!}</span>
                    <span class="side-label">Settings Admin</span>
                </a>
            @endif
        </nav>
    </div>

    <div class="side-footer hidden md:block">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ strtoupper($role) }}</div>
            </div>
            <form method="post" action="{{ route('logout') }}" class="ml-auto shrink-0">
                @csrf
                <button type="submit" class="sidebar-logout-btn" title="Logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
