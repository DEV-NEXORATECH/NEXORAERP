@php
    $routeName = request()->route()?->getName();
    $filterConfigs = [
        'reimbursements.index-page' => ['title' => 'Filter Reimbursements', 'icon' => 'reimbursement', 'search' => 'Karyawan, kategori, catatan...', 'status' => ['pending', 'approved', 'paid', 'rejected'], 'project' => true, 'date' => true],
        'finance.invoices.index' => ['title' => 'Filter Invoices', 'icon' => 'invoice', 'search' => 'Nomor invoice, notes...', 'status' => ['draft', 'sent', 'partial', 'paid', 'void'], 'project' => true, 'date' => true],
        'finance.index' => ['title' => 'Filter Invoices', 'icon' => 'invoice', 'search' => 'Nomor invoice, notes...', 'status' => ['draft', 'sent', 'partial', 'paid', 'void'], 'project' => true, 'date' => true],
        'finance.chart-accounts.index' => ['title' => 'Filter Chart of Accounts', 'icon' => 'master', 'search' => 'Kode atau nama akun...', 'type' => ['asset', 'liability', 'equity', 'revenue', 'expense']],
        'finance.journal-entries.index' => ['title' => 'Filter Journal Entries', 'icon' => 'audit', 'search' => 'Reference atau memo...', 'date' => true],
        'finance.recurring-billings.index' => ['title' => 'Filter Recurring Billings', 'icon' => 'cashflow', 'search' => 'Judul billing...', 'status' => ['active', 'paused', 'cancelled'], 'date' => true],
        'finance.payment-reminders.index' => ['title' => 'Filter Payment Reminders', 'icon' => 'reimbursement', 'search' => 'Subject atau message...', 'status' => ['pending', 'sent', 'done'], 'date' => true],
        'finance.vendor-bills.index' => ['title' => 'Filter Vendor Bills', 'icon' => 'invoice', 'search' => 'Vendor atau nomor bill...', 'status' => ['draft', 'approved', 'partial', 'paid'], 'project' => true, 'bank' => true, 'date' => true],
        'finance.vendor-payments.index' => ['title' => 'Filter Vendor Payments', 'icon' => 'salary', 'search' => 'Reference atau notes...', 'bank' => true, 'date' => true],
        'finance.budgets.index' => ['title' => 'Filter Budgets', 'icon' => 'reports', 'search' => 'Periode atau notes...', 'project' => true, 'coa' => true],
        'finance.tax-rules.index' => ['title' => 'Filter Tax Rules', 'icon' => 'settings', 'search' => 'Nama pajak...', 'tax_type' => ['PPN', 'PPh21', 'PPh23', 'PPh4(2)']],
        'finance.fixed-assets.index' => ['title' => 'Filter Fixed Assets', 'icon' => 'cashflow', 'search' => 'Nama aset...', 'date' => true],
        'finance.currency-rates.index' => ['title' => 'Filter Currency Rates', 'icon' => 'cashflow', 'search' => 'Currency...', 'date' => true],
        'finance.revenue-schedules.index' => ['title' => 'Filter Revenue Schedules', 'icon' => 'reports', 'search' => 'Schedule atau notes...', 'status' => ['planned', 'recognized', 'cancelled'], 'project' => true, 'date' => true],
        'finance.bank-reconciliations.index' => ['title' => 'Filter Bank Reconciliations', 'icon' => 'audit', 'search' => 'Reference atau notes...', 'bank' => true, 'date' => true],
        'finance.purchase-matches.index' => ['title' => 'Filter Purchase Matches', 'icon' => 'master', 'search' => 'Reference atau notes...', 'status' => ['matched', 'partial', 'unmatched'], 'date' => true],
    ];

    $filterConfig = $filterConfigs[$routeName] ?? null;
@endphp

@if($filterConfig)
    @php
        $filterProjects = ($filterConfig['project'] ?? false) ? \App\Models\Project::orderBy('code')->get(['id', 'code', 'name']) : collect();
        $filterBanks = ($filterConfig['bank'] ?? false) ? \App\Models\BankAccount::orderBy('name')->get(['id', 'name']) : collect();
        $filterCoa = ($filterConfig['coa'] ?? false) ? \App\Models\ChartAccount::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name']) : collect();
    @endphp

    <section class="filter-panel section">
        <div class="filter-panel-header">
            <div class="filter-panel-icon">{!! $icon($filterConfig['icon']) !!}</div>
            <div>
                <h3>{{ $filterConfig['title'] }}</h3>
                <p class="muted">Saring data sesuai kebutuhan modul, lalu reset untuk kembali ke semua data.</p>
            </div>
        </div>

        <form method="get" action="{{ url()->current() }}" class="filter-grid">
            <div class="filter-field xl:col-span-2">
                <label>Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $filterConfig['search'] }}">
            </div>

            @if(! empty($filterConfig['status']))
                <div class="filter-field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        @foreach($filterConfig['status'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if(! empty($filterConfig['type']))
                <div class="filter-field">
                    <label>Tipe</label>
                    <select name="type">
                        <option value="">Semua Tipe</option>
                        @foreach($filterConfig['type'] as $type)
                            <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if(! empty($filterConfig['tax_type']))
                <div class="filter-field">
                    <label>Tax Type</label>
                    <select name="tax_type">
                        <option value="">Semua Pajak</option>
                        @foreach($filterConfig['tax_type'] as $tax)
                            <option value="{{ $tax }}" @selected(request('tax_type') === $tax)>{{ $tax }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($filterProjects->isNotEmpty())
                <div class="filter-field">
                    <label>Project</label>
                    <select name="project_id">
                        <option value="">Semua Project</option>
                        @foreach($filterProjects as $project)
                            <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($filterBanks->isNotEmpty())
                <div class="filter-field">
                    <label>Bank / Kas</label>
                    <select name="bank_account_id">
                        <option value="">Semua Bank</option>
                        @foreach($filterBanks as $bank)
                            <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($filterCoa->isNotEmpty())
                <div class="filter-field">
                    <label>CoA</label>
                    <select name="chart_account_id">
                        <option value="">Semua Akun</option>
                        @foreach($filterCoa as $account)
                            <option value="{{ $account->id }}" @selected((int) request('chart_account_id') === $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if(! empty($filterConfig['date']))
                <div class="filter-field">
                    <label>Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-field">
                    <label>Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            @endif

            <div class="filter-actions xl:col-span-6">
                <button type="submit">Terapkan Filter</button>
                <a class="button ghost" href="{{ url()->current() }}">Reset</a>
            </div>
        </form>
    </section>
@endif
