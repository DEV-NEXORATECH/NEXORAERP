@php
    $routeName = request()->route()?->getName();
    $skipFilterRoutes = [
        'dashboard', 'modules.index', 'modules.show', 'approvals.index',
        'cashflows.index-page',
    ];

    $configs = [
        'projects.index' => ['title' => 'Filter Projects', 'icon' => 'projects', 'search' => 'Kode, nama project...', 'status' => ['planning', 'active', 'done', 'hold'], 'client' => true, 'date' => true],
        'sales.index' => ['title' => 'Filter Proposals', 'icon' => 'proposal', 'search' => 'Nomor, judul proposal...', 'status' => ['draft', 'sent', 'approved', 'rejected'], 'project' => true, 'date' => true],
        'sales-inquiries.index' => ['title' => 'Filter Sales Inquiries', 'icon' => 'cashflow', 'search' => 'Nama company, kebutuhan...', 'status' => ['new', 'qualified', 'lost'], 'user' => true, 'date' => true],
        'sales-leads.index' => ['title' => 'Filter Sales Leads', 'icon' => 'projects', 'search' => 'Lead name, company...', 'status' => ['new', 'contacted', 'won', 'lost'], 'client' => true, 'user' => true, 'date' => true],
        'sales-orders.index' => ['title' => 'Filter Sales Orders', 'icon' => 'invoice', 'search' => 'Nomor order, notes...', 'status' => ['draft', 'confirmed', 'delivered', 'cancelled'], 'client' => true, 'date' => true],
        'sales-targets.index' => ['title' => 'Filter Sales Targets', 'icon' => 'reports', 'search' => 'Periode atau notes...', 'user' => true],
        'sales-commissions.index' => ['title' => 'Filter Sales Commissions', 'icon' => 'salary', 'search' => 'Periode atau notes...', 'status' => ['draft', 'approved', 'paid'], 'user' => true, 'date' => true],
        'client-contracts.index' => ['title' => 'Filter Client Contracts', 'icon' => 'master', 'search' => 'Nomor kontrak, scope...', 'status' => ['draft', 'active', 'expired', 'terminated'], 'client' => true, 'date' => true],

        'hr.index' => ['title' => 'Filter Employees', 'icon' => 'employees', 'search' => 'Nama, email, phone...', 'department' => true, 'date' => true],
        'employee-skills.index' => ['title' => 'Filter Employee Skills', 'icon' => 'master', 'search' => 'Nama skill, level...', 'employee' => true],
        'attendances.index' => ['title' => 'Filter Attendances', 'icon' => 'audit', 'search' => 'Notes...', 'employee' => true, 'date' => true],
        'timesheets.index' => ['title' => 'Filter Timesheets', 'icon' => 'projects', 'search' => 'Task, notes...', 'employee' => true, 'project' => true, 'date' => true],
        'leave-requests.index' => ['title' => 'Filter Leave Requests', 'icon' => 'reimbursement', 'search' => 'Reason...', 'status' => ['pending', 'approved', 'rejected'], 'employee' => true, 'date' => true],
        'performance-reviews.index' => ['title' => 'Filter Performance Reviews', 'icon' => 'reports', 'search' => 'Period, notes...', 'employee' => true, 'date' => true],
        'payroll-benefits.index' => ['title' => 'Filter Payroll Benefits', 'icon' => 'salary', 'search' => 'Benefit name, notes...', 'employee' => true, 'date' => true],
        'salaries.index-page' => ['title' => 'Filter Salaries', 'icon' => 'salary', 'search' => 'Periode, notes...', 'status' => ['draft', 'approved', 'paid'], 'employee' => true, 'project' => true],

        'vendors.index' => ['title' => 'Filter Vendors', 'icon' => 'users', 'search' => 'Nama vendor, email, phone...', 'date' => true],
        'purchase-requisitions.index' => ['title' => 'Filter Purchase Requisitions', 'icon' => 'proposal', 'search' => 'Nomor PR, notes...', 'status' => ['draft', 'submitted', 'approved', 'rejected'], 'department' => true, 'user' => true, 'date' => true],
        'purchase-orders.index' => ['title' => 'Filter Purchase Orders', 'icon' => 'projects', 'search' => 'Nomor PO, notes...', 'status' => ['draft', 'sent', 'received', 'cancelled'], 'date' => true],
        'goods-receipts.index' => ['title' => 'Filter Goods Receipts', 'icon' => 'audit', 'search' => 'Nomor receipt, notes...', 'status' => ['draft', 'verified', 'rejected'], 'date' => true],
        'procurement-contracts.index' => ['title' => 'Filter Procurement Contracts', 'icon' => 'invoice', 'search' => 'Nomor kontrak, vendor...', 'status' => ['draft', 'active', 'expired', 'terminated'], 'date' => true],

        'user-management.index' => ['title' => 'Filter Users', 'icon' => 'users', 'search' => 'Nama atau email...', 'type' => ['admin', 'hr', 'finance', 'sales']],
        'clients.index' => ['title' => 'Filter Clients', 'icon' => 'master', 'search' => 'Nama client, PIC, email...', 'date' => true],
        'departments.index' => ['title' => 'Filter Departments', 'icon' => 'employees', 'search' => 'Nama departemen...'],
        'job-positions.index' => ['title' => 'Filter Job Positions', 'icon' => 'salary', 'search' => 'Nama posisi...'],
        'expense-categories.index' => ['title' => 'Filter Expense Categories', 'icon' => 'cashflow', 'search' => 'Nama kategori...'],
        'bank-accounts.index' => ['title' => 'Filter Bank Accounts', 'icon' => 'invoice', 'search' => 'Nama akun, bank, nomor rekening...'],
        'audit-logs.index' => ['title' => 'Filter Audit Logs', 'icon' => 'audit', 'search' => 'Action, description...', 'user' => true, 'date' => true],
    ];

    $config = in_array($routeName, $skipFilterRoutes, true) ? null : ($configs[$routeName] ?? null);
@endphp

@if($config)
    @php
        $filterProjects = ($config['project'] ?? false) ? \App\Models\Project::orderBy('code')->get(['id', 'code', 'name']) : collect();
        $filterEmployees = ($config['employee'] ?? false) ? \App\Models\Employee::orderBy('name')->get(['id', 'name']) : collect();
        $filterClients = ($config['client'] ?? false) ? \App\Models\Client::orderBy('name')->get(['id', 'name']) : collect();
        $filterDepartments = ($config['department'] ?? false) ? \App\Models\Department::orderBy('name')->get(['id', 'name']) : collect();
        $filterUsers = ($config['user'] ?? false) ? \App\Models\User::orderBy('name')->get(['id', 'name', 'role']) : collect();
    @endphp

    <section class="filter-panel section">
        <div class="filter-panel-header">
            <div class="filter-panel-icon">{!! $icon($config['icon']) !!}</div>
            <div>
                <h3>{{ $config['title'] }}</h3>
                <p class="muted">Saring data sesuai kebutuhan modul ini.</p>
            </div>
        </div>

        <form method="get" action="{{ url()->current() }}" class="filter-grid">
            <div class="filter-field xl:col-span-2">
                <label>Cari</label>
                <input name="search" value="{{ request('search') }}" placeholder="{{ $config['search'] }}">
            </div>

            @if(! empty($config['status']))
                <div class="filter-field"><label>Status</label><select name="status"><option value="">Semua Status</option>@foreach($config['status'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            @endif

            @if(! empty($config['type']))
                <div class="filter-field"><label>Tipe</label><select name="type"><option value="">Semua Tipe</option>@foreach($config['type'] as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>@endforeach</select></div>
            @endif

            @if($filterProjects->isNotEmpty())
                <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua Project</option>@foreach($filterProjects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }}</option>@endforeach</select></div>
            @endif

            @if($filterEmployees->isNotEmpty())
                <div class="filter-field"><label>Employee</label><select name="employee_id"><option value="">Semua Employee</option>@foreach($filterEmployees as $employee)<option value="{{ $employee->id }}" @selected((int) request('employee_id') === $employee->id)>{{ $employee->name }}</option>@endforeach</select></div>
            @endif

            @if($filterClients->isNotEmpty())
                <div class="filter-field"><label>Client</label><select name="client_id"><option value="">Semua Client</option>@foreach($filterClients as $client)<option value="{{ $client->id }}" @selected((int) request('client_id') === $client->id)>{{ $client->name }}</option>@endforeach</select></div>
            @endif

            @if($filterDepartments->isNotEmpty())
                <div class="filter-field"><label>Department</label><select name="department_id"><option value="">Semua Department</option>@foreach($filterDepartments as $department)<option value="{{ $department->id }}" @selected((int) request('department_id') === $department->id)>{{ $department->name }}</option>@endforeach</select></div>
            @endif

            @if($filterUsers->isNotEmpty())
                <div class="filter-field"><label>User</label><select name="user_id"><option value="">Semua User</option>@foreach($filterUsers as $user)<option value="{{ $user->id }}" @selected((int) request('user_id') === $user->id)>{{ $user->name }} - {{ strtoupper($user->role) }}</option>@endforeach</select></div>
            @endif

            @if(! empty($config['date']))
                <div class="filter-field"><label>Dari</label><input type="date" name="date_from" value="{{ request('date_from') }}"></div>
                <div class="filter-field"><label>Sampai</label><input type="date" name="date_to" value="{{ request('date_to') }}"></div>
            @endif

            <div class="filter-actions xl:col-span-6">
                <button type="submit">Terapkan Filter</button>
                <a class="button ghost" href="{{ url()->current() }}">Reset</a>
            </div>
        </form>
    </section>
@endif
