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
            <a class="{{ $isPage('modules', 'projects', 'project-create', 'project-edit', 'sales', 'sales-crm', 'sales-inquiries', 'sales-inquiries.create-page', 'sales-inquiries.edit-page', 'sales-leads', 'sales-leads.create-page', 'sales-leads.edit-page', 'sales-orders', 'sales-orders.create-page', 'sales-orders.edit-page', 'sales-targets', 'sales-targets.create-page', 'sales-targets.edit-page', 'sales-commissions', 'sales-commissions.create-page', 'sales-commissions.edit-page', 'client-contracts', 'client-contracts.create-page', 'client-contracts.edit-page', 'proposal-create', 'proposal-edit', 'employees', 'hris', 'employee-create', 'employee-edit', 'employee-skills', 'employee-skill-create', 'employee-skill-edit', 'attendances', 'attendance-create', 'attendance-edit', 'timesheets', 'timesheet-create', 'timesheet-edit', 'leave-requests', 'leave-request-create', 'leave-request-edit', 'performance-reviews', 'performance-review-create', 'performance-review-edit', 'payroll-benefits', 'payroll-benefit-create', 'payroll-benefit-edit', 'salaries', 'salary-create', 'salary-edit', 'reimbursements', 'reimbursement-create', 'reimbursement-edit', 'cashflows', 'cashflow-create', 'cashflow-edit', 'invoices', 'invoice-create', 'invoice-edit', 'payment-create', 'finance-suite', 'chart-accounts', 'chart-account-create', 'chart-account-edit', 'journal-entries', 'journal-entry-create', 'journal-entry-edit', 'recurring-billings', 'recurring-billing-create', 'recurring-billing-edit', 'payment-reminders', 'payment-reminder-create', 'payment-reminder-edit', 'vendor-bills', 'vendor-bill-create', 'vendor-bill-edit', 'vendor-payments', 'vendor-payment-create', 'vendor-payment-edit', 'budgets', 'budget-create', 'budget-edit', 'tax-rules', 'tax-rule-create', 'tax-rule-edit', 'fixed-assets', 'fixed-assets.create-page', 'fixed-assets.edit-page', 'currency-rates', 'currency-rates.create-page', 'currency-rates.edit-page', 'currency-variances', 'currency-variances.create-page', 'currency-variances.edit-page', 'revenue-schedules', 'revenue-schedules.create-page', 'revenue-schedules.edit-page', 'bank-reconciliation-items', 'bank-reconciliation-items.create-page', 'bank-reconciliation-items.edit-page', 'purchase-matches', 'purchase-matches.create-page', 'purchase-matches.edit-page', 'finance-advanced', 'vendors', 'vendor-create', 'vendor-edit', 'purchase-requisitions', 'purchase-requisition-create', 'purchase-requisition-edit', 'purchase-orders', 'purchase-order-create', 'purchase-order-edit', 'goods-receipts', 'goods-receipt-create', 'goods-receipt-edit', 'procurement-contracts', 'procurement-contract-create', 'procurement-contract-edit') ? 'active' : '' }}" href="{{ route('modules.index') }}">
                <span class="side-icon">{!! $icon('list') !!}</span>
                <span class="side-label">Module</span>
            </a>
            <a class="{{ $isPage('approvals') ? 'active' : '' }}" href="{{ route('approvals.index') }}">
                <span class="side-icon">{!! $icon('audit') !!}</span>
                <span class="side-label">Approval & Task</span>
            </a>
            @if($can('admin', 'finance'))
                <a class="{{ $isPage('reports') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <span class="side-icon">{!! $icon('reports') !!}</span>
                    <span class="side-label">Report & Analyst</span>
                </a>
            @endif
            @if($can('admin'))
                <a class="{{ $isPage('company', 'users', 'masters', 'trash', 'audit') ? 'active' : '' }}" href="{{ route('settings-admin.index') }}">
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
