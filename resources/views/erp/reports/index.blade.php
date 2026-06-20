@extends('layouts.erp', ['activePage' => 'reports', 'pageTitle' => 'Customize Reports'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Reports & Analysis</h1>
        <p>Customize laporan finance, project, pajak, cashflow, aging, dan reconciliation dari satu workspace.</p>
    </div>
    <div class="report-hero-card">
        <small>Current Report</small>
        <strong>{{ $reportOptions[$reportType] ?? 'Report' }}</strong>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('reports') !!}</div>
        <div>
            <h3>Customize Report</h3>
            <p class="muted">Pilih jenis laporan, periode, project, dan bank/kas.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.index') }}" class="filter-grid">
        <div class="filter-field xl:col-span-2">
            <label>Jenis Laporan</label>
            <select name="report">
                @foreach($reportOptions as $key => $label)
                    <option value="{{ $key }}" @selected($reportType === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
        <div class="filter-field"><label>Bank/Kas</label><select name="bank_account_id"><option value="">Semua</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.index') }}">Reset</a>
            <a class="button ghost" href="{{ route('exports.cashflows') }}">Export Cashflows</a>
            <a class="button ghost" href="{{ route('exports.project-finance') }}">Export Project Finance</a>
        </div>
    </form>
</section>

<section class="report-summary-grid section">
    <div class="stat-card"><div class="stat-card-label">Revenue</div><div class="stat-card-metric good">{{ $rp($profitLoss['revenue']) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Expense</div><div class="stat-card-metric bad">{{ $rp($profitLoss['expense']) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Net Profit / Cash</div><div class="stat-card-metric {{ $profitLoss['net_profit'] >= 0 ? 'good' : 'bad' }}">{{ $rp($profitLoss['net_profit']) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Report</div><div class="stat-card-metric text-lg">{{ $reportOptions[$reportType] ?? 'Report' }}</div></div>
</section>

@if($reportType === 'profit_loss')
<section class="grid two section">
    <div class="card">
        <h2>Laporan Laba Rugi</h2>
        <table>
            <tr><td>Revenue</td><td class="good text-right font-black">{{ $rp($profitLoss['revenue']) }}</td></tr>
            <tr><td>Expense</td><td class="bad text-right font-black">{{ $rp($profitLoss['expense']) }}</td></tr>
            <tr><td>Net Profit / Loss</td><td class="{{ $profitLoss['net_profit'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($profitLoss['net_profit']) }}</td></tr>
        </table>
    </div>
    <div class="card">
        <h2>Expense by Category</h2>
        <table>
            @forelse($profitLoss['expense_by_category'] as $category => $amount)
                <tr><td>{{ $category }}</td><td class="text-right font-bold">{{ $rp($amount) }}</td></tr>
            @empty
                <tr><td colspan="2" class="py-8 text-center text-slate-500">Belum ada expense.</td></tr>
            @endforelse
        </table>
    </div>
</section>
@endif

@if($reportType === 'balance_sheet')
<section class="grid three section">
    @foreach(['assets' => 'Assets', 'liabilities' => 'Liabilities', 'equity' => 'Equity'] as $key => $label)
        <div class="card">
            <h2>{{ $label }}</h2>
            <table>
                @forelse($balanceSheet[$key] as $row)
                    <tr><td>{{ $row['account']->code }} - {{ $row['account']->name }}</td><td class="text-right font-bold">{{ $rp($row['balance']) }}</td></tr>
                @empty
                    <tr><td colspan="2" class="py-8 text-center text-slate-500">Belum ada jurnal.</td></tr>
                @endforelse
            </table>
        </div>
    @endforeach
    <div class="card">
        <h2>Balance Check</h2>
        <table>
            <tr><td>Total Assets</td><td class="text-right font-black">{{ $rp($balanceSheet['total_assets']) }}</td></tr>
            <tr><td>Liabilities + Equity</td><td class="text-right font-black">{{ $rp($balanceSheet['total_liabilities'] + $balanceSheet['total_equity']) }}</td></tr>
        </table>
    </div>
</section>
@endif

@if($reportType === 'cash_flow_statement')
<section class="card section">
    <h2>Laporan Arus Kas</h2>
    <table>
        <tr><td>Cash In</td><td class="good text-right font-black">{{ $rp($cashFlowStatement['operating_in']) }}</td></tr>
        <tr><td>Cash Out</td><td class="bad text-right font-black">{{ $rp($cashFlowStatement['operating_out']) }}</td></tr>
        <tr><td>Net Cash Flow</td><td class="{{ $cashFlowStatement['net_cash'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($cashFlowStatement['net_cash']) }}</td></tr>
    </table>
    <div class="wide section">
        <table><thead><tr><th>Bulan</th><th>Income</th><th>Expense</th><th>Net</th></tr></thead><tbody>
            @foreach($cashFlowStatement['by_month'] as $month => $row)
                <tr><td>{{ $month }}</td><td>{{ $rp($row['income']) }}</td><td>{{ $rp($row['expense']) }}</td><td>{{ $rp($row['balance']) }}</td></tr>
            @endforeach
        </tbody></table>
    </div>
</section>
@endif

@if($reportType === 'project_profitability')
<section class="section" id="project-finance">
    <div class="section-head"><h2>Laporan Profitabilitas Proyek</h2><span class="badge">{{ $projectReports->count() }} project</span></div>
    <div class="grid three">
        @forelse ($projectReports as $report)
            <div class="card">
                <h3>{{ $report['project']->code }} - {{ $report['project']->name }}</h3>
                <table>
                    <tr><td>Kontrak</td><td class="text-right font-bold">{{ $rp($report['project']->contract_value) }}</td></tr>
                    <tr><td>Income</td><td class="good text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                    <tr><td>Expense</td><td class="bad text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                    <tr><td>Profit/Loss</td><td class="{{ $report['summary']['balance'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                    <tr><td>Margin</td><td class="text-right font-bold">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>
@endif

@if(in_array($reportType, ['aging_ar', 'aging_ap'], true))
@php $aging = $reportType === 'aging_ar' ? $agingAr : $agingAp; @endphp
<section class="grid cards section">
    @foreach($aging['buckets'] as $bucket => $amount)
        <div class="stat-card"><div class="stat-card-label">{{ str_replace('_', '-', $bucket) }} hari</div><div class="stat-card-metric">{{ $rp($amount) }}</div></div>
    @endforeach
</section>
<section class="card section wide">
    <h2>{{ $reportType === 'aging_ar' ? 'Laporan Umur Piutang' : 'Laporan Umur Utang' }}</h2>
    <table><thead><tr><th>No</th><th>Due Date</th><th>Days</th><th>Bucket</th><th>Outstanding</th></tr></thead><tbody>
        @foreach($aging['items'] as $item)
            <tr><td>{{ $item['row']->number ?? $item['row']->bill_number }}</td><td>{{ $item['row']->due_date }}</td><td>{{ $item['days'] }}</td><td><span class="badge badge-pending">{{ $item['bucket'] }}</span></td><td>{{ $rp($item['outstanding']) }}</td></tr>
        @endforeach
    </tbody></table>
</section>
@endif

@if($reportType === 'tax_summary')
<section class="grid two section">
    <div class="card">
        <h2>Laporan Rekapitulasi Pajak</h2>
        <table>
            <tr><td>Output Tax dari Invoice</td><td class="text-right font-bold">{{ $rp($taxSummary['invoice_tax']) }}</td></tr>
            <tr><td>Input/Withholding Tax dari Vendor Bill</td><td class="text-right font-bold">{{ $rp($taxSummary['vendor_tax']) }}</td></tr>
            <tr><td>Net Tax</td><td class="text-right font-black">{{ $rp($taxSummary['net_tax']) }}</td></tr>
        </table>
    </div>
    <div class="card">
        <h2>Active Tax Rules</h2>
        <table>
            @foreach($taxSummary['rules'] as $rule)
                <tr><td>{{ $rule->name }}<br><span class="muted">{{ $rule->tax_type }} - {{ $rule->direction }}</span></td><td class="text-right">{{ $rule->rate }}%</td></tr>
            @endforeach
        </table>
    </div>
</section>
@endif

@if($reportType === 'budget_vs_actual')
<section class="card section wide">
    <h2>Laporan Anggaran vs Realisasi</h2>
    <table><thead><tr><th>Period</th><th>Project / Account</th><th>Budget</th><th>Actual</th><th>Variance</th></tr></thead><tbody>
        @foreach($budgetVsActual as $row)
            <tr><td>{{ $row['budget']->period }}</td><td>{{ $row['budget']->project?->code ?? 'Company' }}<br><span class="muted">{{ $row['budget']->account?->code }} {{ $row['budget']->account?->name }}</span></td><td>{{ $rp($row['budget']->budget_amount) }}</td><td>{{ $rp($row['actual']) }}</td><td class="{{ $row['variance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($row['variance']) }}</td></tr>
        @endforeach
    </tbody></table>
</section>
@endif

@if($reportType === 'transactions')
<section class="card section wide">
    <h2>Transaction Listings</h2>
    <table><thead><tr><th>Tanggal</th><th>Project</th><th>Type</th><th>Category</th><th>Bank</th><th>Amount</th></tr></thead><tbody>
        @foreach($transactions as $flow)
            <tr><td>{{ $flow->transaction_date }}</td><td>{{ $flow->project?->code ?? 'Company' }}</td><td><span class="badge badge-{{ $flow->type }}">{{ $flow->type }}</span></td><td>{{ $flow->category }}</td><td>{{ $flow->bankAccount?->name ?? '-' }}</td><td>{{ $rp($flow->amount) }}</td></tr>
        @endforeach
    </tbody></table>
    <div class="pager">{{ $transactions->links() }}</div>
</section>
@endif

@if($reportType === 'bank_reconciliation')
<section class="card section wide">
    <h2>Bank Reconciliation</h2>
    <table><thead><tr><th>Bank/Kas</th><th>Opening</th><th>Cash In</th><th>Cash Out</th><th>Book Balance</th><th>Unreconciled</th></tr></thead><tbody>
        @foreach($bankReconciliation as $row)
            <tr><td class="font-bold">{{ $row['bank']->name }}<br><span class="muted">{{ $row['bank']->bank_name }} {{ $row['bank']->account_number }}</span></td><td>{{ $rp($row['bank']->opening_balance) }}</td><td>{{ $rp($row['income']) }}</td><td>{{ $rp($row['expense']) }}</td><td class="font-black">{{ $rp($row['book_balance']) }}</td><td>{{ $rp($row['unreconciled']) }}</td></tr>
        @endforeach
    </tbody></table>
</section>
@endif
@endsection
