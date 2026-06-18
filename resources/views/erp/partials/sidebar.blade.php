<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo">NX</div>
        <div>
            <div class="sidebar-title">NEXORA ERP</div>
            <div class="sidebar-subtitle">{{ strtoupper($role) }} workspace</div>
        </div>
    </div>

    <div class="side-section">Main</div>
    <nav class="side-nav">
        <a class="{{ $isPage('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="side-icon">{!! $icon('dashboard') !!}</span> <span class="side-label">Dashboard</span></a>
    </nav>

    @if($can('admin', 'sales'))
        <div class="side-section">Sales</div>
        <nav class="side-nav">
            <details class="side-group" {{ $isPage('projects', 'project-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('projects', 'project-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('projects') !!}</span>
                    <span class="side-label">Projects</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('projects') ? 'active sub' : 'sub' }}" href="{{ route('projects.index') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('project-create') ? 'active sub' : 'sub' }}" href="{{ route('projects.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
            <details class="side-group" {{ $isPage('sales', 'proposal-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('sales', 'proposal-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('proposal') !!}</span>
                    <span class="side-label">Proposals</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('sales') ? 'active sub' : 'sub' }}" href="{{ route('sales.index') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('proposal-create') ? 'active sub' : 'sub' }}" href="{{ route('proposals.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
        </nav>
    @endif

    @if($can('admin', 'hr'))
        <div class="side-section">HR</div>
        <nav class="side-nav">
            <details class="side-group" {{ $isPage('employees', 'employee-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('employees', 'employee-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('employees') !!}</span>
                    <span class="side-label">Employees</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('employees') ? 'active sub' : 'sub' }}" href="{{ route('hr.index') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('employee-create') ? 'active sub' : 'sub' }}" href="{{ route('employees.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
            <details class="side-group" {{ $isPage('salaries', 'salary-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('salaries', 'salary-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('salary') !!}</span>
                    <span class="side-label">Salary</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('salaries') ? 'active sub' : 'sub' }}" href="{{ route('salaries.index-page') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('salary-create') ? 'active sub' : 'sub' }}" href="{{ route('salaries.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
        </nav>
    @endif

    @if($can('admin', 'finance'))
        <div class="side-section">Finance</div>
        <nav class="side-nav">
            <details class="side-group" {{ $isPage('reimbursements', 'reimbursement-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('reimbursements', 'reimbursement-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('reimbursement') !!}</span>
                    <span class="side-label">Reimbursements</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('reimbursements') ? 'active sub' : 'sub' }}" href="{{ route('reimbursements.index-page') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('reimbursement-create') ? 'active sub' : 'sub' }}" href="{{ route('reimbursements.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
            <details class="side-group" {{ $isPage('cashflows', 'cashflow-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('cashflows', 'cashflow-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('cashflow') !!}</span>
                    <span class="side-label">Cashflow</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('cashflows') ? 'active sub' : 'sub' }}" href="{{ route('cashflows.index-page') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('cashflow-create') ? 'active sub' : 'sub' }}" href="{{ route('cashflows.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Tambah</a>
                </div>
            </details>
            <details class="side-group" {{ $isPage('invoices', 'invoice-create', 'payment-create') ? 'open' : '' }}>
                <summary class="side-summary {{ $isPage('invoices', 'invoice-create', 'payment-create') ? 'active' : '' }}">
                    <span class="side-icon">{!! $icon('invoice') !!}</span>
                    <span class="side-label">Invoices</span>
                </summary>
                <div class="side-subs">
                    <a class="{{ $isPage('invoices') ? 'active sub' : 'sub' }}" href="{{ route('finance.index') }}"><span class="side-icon">{!! $icon('list') !!}</span> List</a>
                    <a class="{{ $isPage('invoice-create') ? 'active sub' : 'sub' }}" href="{{ route('invoices.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Buat</a>
                    <a class="{{ $isPage('payment-create') ? 'active sub' : 'sub' }}" href="{{ route('payments.create-page') }}"><span class="side-icon">{!! $icon('plus') !!}</span> Payment</a>
                </div>
            </details>
        </nav>
    @endif

    @if($can('admin'))
        <div class="side-section">Admin</div>
        <nav class="side-nav">
            <a class="{{ $isPage('company') ? 'active' : '' }}" href="{{ route('admin.index') }}"><span class="side-icon">{!! $icon('settings') !!}</span> <span class="side-label">Company Setting</span></a>
            <a class="{{ $isPage('users') ? 'active' : '' }}" href="{{ route('admin.users') }}"><span class="side-icon">{!! $icon('users') !!}</span> <span class="side-label">Users</span></a>
            <a class="{{ $isPage('masters') ? 'active' : '' }}" href="{{ route('admin.masters') }}"><span class="side-icon">{!! $icon('master') !!}</span> <span class="side-label">Master Data</span></a>
            <a class="{{ $isPage('trash') ? 'active' : '' }}" href="{{ route('admin.trash') }}"><span class="side-icon">{!! $icon('trash') !!}</span> <span class="side-label">Trash</span></a>
            <a class="{{ $isPage('audit') ? 'active' : '' }}" href="{{ route('admin.audit') }}"><span class="side-icon">{!! $icon('audit') !!}</span> <span class="side-label">Audit Log</span></a>
        </nav>
    @endif

    <div class="side-section">Reports</div>
    <nav class="side-nav">
        @if($can('admin', 'finance'))
            <a class="{{ $isPage('reports') ? 'active' : '' }}" href="{{ route('reports.index') }}"><span class="side-icon">{!! $icon('reports') !!}</span> <span class="side-label">Reports</span></a>
        @endif
        @if($can('admin'))
            <a href="{{ route('backup.database') }}"><span class="side-icon">{!! $icon('backup') !!}</span> <span class="side-label">Backup Database</span></a>
        @endif
    </nav>

    <div class="side-footer">
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="ghost">Logout</button>
        </form>
    </div>
</aside>
