<aside class="sidebar">

    {{-- ── Brand header ──────────────────────────────────────────────── --}}
    <div class="sidebar-head">
        <div class="sidebar-brand">
            <div class="logo">NX</div>
            <div class="min-w-0 flex-1">
                <div class="sidebar-title">NEXORA ERP</div>
                <div class="sidebar-subtitle">{{ strtoupper($role) }} workspace</div>
            </div>
        </div>
    </div>

    {{-- ── Scrollable nav area ────────────────────────────────────────── --}}
    <div class="sidebar-scroll">

        <div class="side-section">Main</div>
        <nav class="side-nav">
            <a class="{{ $isPage('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="side-icon">{!! $icon('dashboard') !!}</span>
                <span class="side-label">Dashboard</span>
            </a>
        </nav>

        @if($can('admin', 'sales'))
            <div class="side-section">Sales</div>
            <nav class="side-nav">
                <a class="{{ $isPage('projects', 'project-create', 'project-edit') ? 'active' : '' }}"
                   href="{{ route('projects.index') }}">
                    <span class="side-icon">{!! $icon('projects') !!}</span>
                    <span class="side-label">Projects</span>
                </a>
                <a class="{{ $isPage('sales', 'proposal-create', 'proposal-edit') ? 'active' : '' }}"
                   href="{{ route('sales.index') }}">
                    <span class="side-icon">{!! $icon('proposal') !!}</span>
                    <span class="side-label">Proposals</span>
                </a>
            </nav>
        @endif

        @if($can('admin', 'hr'))
            <div class="side-section">HR</div>
            <nav class="side-nav">
                <a class="{{ $isPage('employees', 'employee-create', 'employee-edit') ? 'active' : '' }}"
                   href="{{ route('hr.index') }}">
                    <span class="side-icon">{!! $icon('employees') !!}</span>
                    <span class="side-label">Employees</span>
                </a>
                <a class="{{ $isPage('salaries', 'salary-create', 'salary-edit') ? 'active' : '' }}"
                   href="{{ route('salaries.index-page') }}">
                    <span class="side-icon">{!! $icon('salary') !!}</span>
                    <span class="side-label">Salary</span>
                </a>
            </nav>
        @endif

        @if($can('admin', 'finance'))
            <div class="side-section">Finance</div>
            <nav class="side-nav">
                <a class="{{ $isPage('reimbursements', 'reimbursement-create', 'reimbursement-edit') ? 'active' : '' }}"
                   href="{{ route('reimbursements.index-page') }}">
                    <span class="side-icon">{!! $icon('reimbursement') !!}</span>
                    <span class="side-label">Reimbursements</span>
                </a>
                <a class="{{ $isPage('cashflows', 'cashflow-create', 'cashflow-edit') ? 'active' : '' }}"
                   href="{{ route('cashflows.index-page') }}">
                    <span class="side-icon">{!! $icon('cashflow') !!}</span>
                    <span class="side-label">Cashflow</span>
                </a>
                <a class="{{ $isPage('invoices', 'invoice-create', 'invoice-edit', 'payment-create') ? 'active' : '' }}"
                   href="{{ route('finance.index') }}">
                    <span class="side-icon">{!! $icon('invoice') !!}</span>
                    <span class="side-label">Invoices</span>
                </a>
            </nav>
        @endif

        @if($can('admin'))
            <div class="side-section">Admin</div>
            <nav class="side-nav">
                <a class="{{ $isPage('company') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                    <span class="side-icon">{!! $icon('settings') !!}</span>
                    <span class="side-label">Company Setting</span>
                </a>
                <a class="{{ $isPage('users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                    <span class="side-icon">{!! $icon('users') !!}</span>
                    <span class="side-label">Users</span>
                </a>
                <a class="{{ $isPage('masters') ? 'active' : '' }}" href="{{ route('admin.masters') }}">
                    <span class="side-icon">{!! $icon('master') !!}</span>
                    <span class="side-label">Master Data</span>
                </a>
                <a class="{{ $isPage('trash') ? 'active' : '' }}" href="{{ route('admin.trash') }}">
                    <span class="side-icon">{!! $icon('trash') !!}</span>
                    <span class="side-label">Trash</span>
                </a>
                <a class="{{ $isPage('audit') ? 'active' : '' }}" href="{{ route('admin.audit') }}">
                    <span class="side-icon">{!! $icon('audit') !!}</span>
                    <span class="side-label">Audit Log</span>
                </a>
            </nav>
        @endif

        <div class="side-section">Reports</div>
        <nav class="side-nav">
            @if($can('admin', 'finance'))
                <a class="{{ $isPage('reports') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <span class="side-icon">{!! $icon('reports') !!}</span>
                    <span class="side-label">Reports</span>
                </a>
            @endif
            @if($can('admin'))
                <a href="{{ route('backup.database') }}">
                    <span class="side-icon">{!! $icon('backup') !!}</span>
                    <span class="side-label">Backup Database</span>
                </a>
            @endif
        </nav>

    </div>{{-- /sidebar-scroll --}}

    {{-- ── User info + logout (fixed bottom) ─────────────────────────── --}}
    <div class="side-footer hidden md:block">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
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
