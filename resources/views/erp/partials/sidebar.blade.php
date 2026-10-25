@php
    use App\Models\FeatureFlag;

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
        'accounting-journals', 'accounting-journals-create', 'accounting-journals-edit',
        'payment-terms', 'payment-terms-create', 'payment-terms-edit',
        'currencies', 'currencies-create', 'currencies-edit',
        'fiscal-periods', 'fiscal-periods-create', 'fiscal-periods-edit',
        'vendors', 'purchase-requisitions', 'purchase-orders', 'goods-receipts', 'procurement-contracts', 'procurement',
        'products', 'product-create', 'product-edit',
        'product-categories', 'product-units', 'warehouses', 'warehouse-create', 'warehouse-edit',
        'stock-locations', 'stock-movements', 'stock-adjustments', 'stock-adjustment-create',
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
        'reports-trial-balance',
        'reports-general-ledger',
    ];

    $settingsPages = [
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
        'feature-flags',
        'feature-flags-create',
        'feature-flags-edit',
    ];

    $cmsPages = [
        'cms',
        'cms-sections',
        'cms-blog',
        'cms-api-settings',
    ];

    $onReportsHub = request()->routeIs('modules.show') && request()->route('section') === 'reports';
    $onAdminHub = request()->routeIs('modules.show') && request()->route('section') === 'admin';
    $onModuleHub = request()->routeIs('modules.index') || (request()->routeIs('modules.show') && ! in_array(request()->route('section'), ['reports', 'admin'], true));
@endphp

<aside class="sidebar">
    <div class="sidebar-head border-0 px-0 py-4">
        <div class="sidebar-brand w-full justify-center">
            <img class="h-10 w-10 rounded-md object-contain" src="{{ asset('images/nexora-logo.png') }}?v=6" alt="NEXORA">
        </div>
    </div>

    <div class="sidebar-scroll">
        <nav class="side-nav">
            <div class="side-section">Navigation</div>

            <a class="side-link {{ request()->routeIs('task-board.index') || (request()->routeIs('approvals.index') && request()->boolean('tasks_only')) ? 'active' : '' }}" href="{{ route('task-board.index') }}">
                <span class="side-icon">{!! $icon('audit') !!}</span>
                <span class="side-label">Task Board</span>
            </a>

            @php
                $isDashboardGroup = request()->routeIs('dashboard*');
            @endphp
            <details class="side-group" {{ $isDashboardGroup ? 'open' : '' }}>
                <summary class="side-summary {{ $isDashboardGroup ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('dashboard') !!}</span>
                    <span class="side-label">Dashboard</span>
                    <svg class="side-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="side-subs">
                    <a class="sub {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'active font-semibold' : '' }}" href="{{ route('dashboard') }}">
                        <span class="side-icon text-slate-300">{!! $icon('dashboard') !!}</span><span class="side-label">Dashboard Utama</span>
                    </a>
                    @if($can('admin', 'finance', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.executive') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.executive') }}">
                            <span class="side-icon text-cyan-300">{!! $icon('reports') !!}</span><span class="side-label">Executive Overview</span>
                        </a>
                    @endif
                    @if($can('admin', 'sales', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.sales') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.sales') }}">
                        <span class="side-icon text-sky-300">{!! $icon('proposal') !!}</span><span class="side-label">Sales & CRM</span>
                        </a>
                    @endif
                    @if($can('admin', 'project', 'sales', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.projects') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.projects') }}">
                        <span class="side-icon text-violet-300">{!! $icon('projects') !!}</span><span class="side-label">Project Management</span>
                        </a>
                    @endif
                    @if($can('admin', 'hr', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.hr') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.hr') }}">
                            <span class="side-icon text-emerald-300">{!! $icon('employees') !!}</span><span class="side-label">Human Resources</span>
                        </a>
                    @endif
                    @if($can('admin', 'finance', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.finance') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.finance') }}">
                        <span class="side-icon text-amber-300">{!! $icon('cashflow') !!}</span><span class="side-label">Finance & Accounting</span>
                        </a>
                    @endif
                    @if($can('admin', 'procurement', 'finance', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.procurement') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.procurement') }}">
                        <span class="side-icon text-orange-300">{!! $icon('procurement') !!}</span><span class="side-label">Procurement</span>
                        </a>
                    @endif
                    @if($can('admin', 'inventory', 'procurement', 'management'))
                        <a class="sub {{ request()->routeIs('dashboard.inventory') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.inventory') }}">
                        <span class="side-icon text-pink-300">{!! $icon('warehouse') !!}</span><span class="side-label">Inventory & Warehouse</span>
                        </a>
                    @endif
                    @if($can('admin', 'cms') && FeatureFlag::moduleEnabled('cms'))
                        <a class="sub {{ request()->routeIs('dashboard.cms') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.cms') }}">
                            <span class="side-icon text-fuchsia-300">{!! $icon('cms') !!}</span><span class="side-label">CMS & Website</span>
                        </a>
                    @endif
                    <a class="sub {{ request()->routeIs('dashboard.workspace') ? 'active font-semibold' : '' }}" href="{{ route('dashboard.workspace') }}">
                        <span class="side-icon text-blue-200">{!! $icon('users') !!}</span><span class="side-label">My Workspace</span>
                    </a>
                </div>
            </details>

            <a class="{{ ($onModuleHub || $isPage(...$modulePages)) && ! $onReportsHub && ! $onAdminHub && ! $isPage(...$reportPages) && ! $isPage(...$settingsPages) ? 'active' : '' }}" href="{{ route('modules.index') }}">
                <span class="side-icon">{!! $icon('list') !!}</span>
                <span class="side-label">Modules</span>
            </a>

            <a class="{{ $isPage('approvals') ? 'active' : '' }}" href="{{ route('approvals.index') }}">
                <span class="side-icon">{!! $icon('audit') !!}</span>
                <span class="side-label">Approval & Task</span>
            </a>

            <div class="side-section">Management</div>

            @if($can('admin', 'finance') && FeatureFlag::moduleEnabled('reports'))
                <a class="{{ $onReportsHub || $isPage(...$reportPages) ? 'active' : '' }}" href="{{ route('modules.show', 'reports') }}">
                    <span class="side-icon">{!! $icon('reports') !!}</span>
                    <span class="side-label">Report & Analyst</span>
                </a>
            @endif

            @if($can('admin') && FeatureFlag::moduleEnabled('cms'))
                <a class="{{ $isPage(...$cmsPages) ? 'active' : '' }}" href="{{ route('cms.index') }}">
                    <span class="side-icon">{!! $icon('cms') !!}</span>
                    <span class="side-label">CMS Portal</span><span class="sr-only" aria-hidden="true">>CMS<</span>
                </a>
            @endif

            @if($can('admin') && FeatureFlag::isEnabled('platform.settings'))
                <a class="{{ $onAdminHub || $isPage(...$settingsPages) ? 'active' : '' }}" href="{{ route('admin.index') }}">
                    <span class="side-icon">{!! $icon('settings') !!}</span>
                    <span class="side-label">Settings Admin</span>
                </a>
            @endif
        </nav>
    </div>

</aside>
