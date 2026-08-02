@extends('layouts.erp', ['activePage' => 'dashboard', 'pageTitle' => 'Dashboard'])

@section('content')
<section class="dashboard-hero">
    <div class="module-detail-copy">
        <a class="module-back-link" href="{{ route('modules.index') }}">Module Hub</a>
        <div class="module-title-row">
            <span class="module-title-icon">{!! $icon('dashboard') !!}</span>
            <div>
                <span class="module-eyebrow">NEXORA Command Center</span>
                <h1>Dashboard</h1>
            </div>
        </div>
        <p>Ringkasan finance, operasional, pending action, dan CMS dalam satu layar kerja.</p>
    </div>
    <div class="dashboard-hero-balance">
        <small>Net Cashflow</small>
        <strong class="{{ $executiveSummary['net_cashflow'] >= 0 ? 'good' : 'bad' }}">{{ $rp($executiveSummary['net_cashflow']) }}</strong>
        <em>{{ $executiveSummary['net_cashflow'] >= 0 ? 'Cash position positif' : 'Cash position perlu perhatian' }}</em>
    </div>
</section>

{{-- Row 1: Executive Summary --}}
<section class="dashboard-section-grid" id="executive-summary">
    <a href="{{ route('cashflows.index-page') }}" class="stat-card no-underline">
        <div class="stat-card-top">
            <div class="stat-card-icon success">{!! $icon('cashflow') !!}</div>
            <span class="stat-card-badge up">Income</span>
        </div>
        <div class="stat-card-metric good">{{ $rp($executiveSummary['income']) }}</div>
        <div class="stat-card-label">Total Income</div>
        <div class="stat-card-sub">Seluruh cashflow masuk</div>
    </a>
    <a href="{{ route('cashflows.index-page') }}" class="stat-card no-underline">
        <div class="stat-card-top">
            <div class="stat-card-icon danger">{!! $icon('cashflow') !!}</div>
            <span class="stat-card-badge down">Expense</span>
        </div>
        <div class="stat-card-metric bad">{{ $rp($executiveSummary['expense']) }}</div>
        <div class="stat-card-label">Total Expense</div>
        <div class="stat-card-sub">Seluruh cashflow keluar</div>
    </a>
    <a href="{{ route('reports.cash-flow') }}" class="stat-card no-underline">
        <div class="stat-card-top">
            <div class="stat-card-icon {{ $executiveSummary['net_cashflow'] >= 0 ? 'success' : 'danger' }}">{!! $icon('reports') !!}</div>
            <span class="stat-card-badge {{ $executiveSummary['net_cashflow'] >= 0 ? 'up' : 'down' }}">{{ $executiveSummary['net_cashflow'] >= 0 ? 'Surplus' : 'Defisit' }}</span>
        </div>
        <div class="stat-card-metric {{ $executiveSummary['net_cashflow'] >= 0 ? 'good' : 'bad' }}">{{ $rp($executiveSummary['net_cashflow']) }}</div>
        <div class="stat-card-label">Net Cashflow</div>
        <div class="stat-card-sub">Income dikurangi expense</div>
    </a>
    <a href="{{ route('projects.index') }}" class="stat-card no-underline">
        <div class="stat-card-top">
            <div class="stat-card-icon">{!! $icon('projects') !!}</div>
            <span class="stat-card-badge neutral">Project</span>
        </div>
        <div class="stat-card-metric">{{ $executiveSummary['active_projects'] }}</div>
        <div class="stat-card-label">Active Projects</div>
        <div class="stat-card-sub">{{ $filterCounts['projects_total'] }} total project</div>
    </a>
    <a href="{{ route('approvals.index') }}" class="stat-card no-underline">
        <div class="stat-card-top">
            <div class="stat-card-icon warn">{!! $icon('audit') !!}</div>
            <span class="stat-card-badge neutral">Action</span>
        </div>
        <div class="stat-card-metric">{{ $executiveSummary['pending_approvals'] }}</div>
        <div class="stat-card-label">Pending Actions</div>
        <div class="stat-card-sub">Approval, task, timesheet</div>
    </a>
</section>

{{-- Row 2: Finance Health --}}
<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Income vs Expense</div>
                <div class="chart-sub">Grouped column chart, 6 bulan terakhir</div>
            </div>
            <a class="badge" href="{{ route('reports.profit-loss') }}">Finance</a>
        </div>
        <div class="mt-6 flex h-64 items-end gap-2 overflow-x-auto px-1">
            @foreach($monthlyChart as $row)
                @php
                    $incomeHeight = max(4, ($row['income'] / $monthlyMax) * 100);
                    $expenseHeight = max(4, ($row['expense'] / $monthlyMax) * 100);
                @endphp
                <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                    <div class="flex h-52 w-full items-end justify-center gap-1.5 rounded-2xl bg-[#f3f8fc] px-2 py-2">
                        <div title="Income {{ $rp($row['income']) }}" class="w-5 rounded-t-lg bg-[#0059A7]" style="height: {{ $incomeHeight }}%"></div>
                        <div title="Expense {{ $rp($row['expense']) }}" class="w-5 rounded-t-lg bg-[#b42318]" style="height: {{ $expenseHeight }}%"></div>
                    </div>
                    <div class="text-[11px] font-black text-slate-500">{{ $row['label'] }}</div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex gap-5 border-t border-[#d7e3ef] pt-4 text-sm font-bold">
            <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#0059A7]"></span> Income</span>
            <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-[#b42318]"></span> Expense</span>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Invoice & Vendor Bill Aging</div>
                <div class="chart-sub">Horizontal stacked aging, nominal outstanding</div>
            </div>
            <a class="badge" href="{{ route('reports.aging', 'ar') }}">Aging</a>
        </div>
        @foreach([['label' => 'Invoice Customer', 'items' => $invoiceAging], ['label' => 'Vendor Bill', 'items' => $vendorBillAging]] as $row)
            @php $total = max(collect($row['items'])->sum('amount'), 1); @endphp
            <div class="mt-5">
                <div class="mb-2 flex items-center justify-between text-sm font-black">
                    <span>{{ $row['label'] }}</span>
                    <span class="text-slate-500">{{ $rp(collect($row['items'])->sum('amount')) }}</span>
                </div>
                <div class="flex h-8 overflow-hidden rounded-xl bg-[#e8f2fb]">
                    @foreach($row['items'] as $bucket)
                        @php $width = $bucket['amount'] > 0 ? max(4, ($bucket['amount'] / $total) * 100) : 0; @endphp
                        <div title="{{ $bucket['label'] }} {{ $rp($bucket['amount']) }}" class="h-full {{ $loop->index === 0 ? 'bg-emerald-500' : ($loop->index === 1 ? 'bg-amber-400' : ($loop->index === 2 ? 'bg-orange-500' : ($loop->index === 3 ? 'bg-red-500' : 'bg-red-800'))) }}" style="width: {{ $width }}%"></div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <div class="mt-5 grid grid-cols-2 gap-2 text-xs font-bold text-slate-500">
            @foreach($invoiceAging as $bucket)
                <span>{{ $bucket['label'] }}</span>
            @endforeach
        </div>
    </div>
</section>

<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Cashflow Trend</div>
                <div class="chart-sub">Net cashflow per bulan</div>
            </div>
            <span class="badge">Net</span>
        </div>
        <div class="mt-6 grid gap-3">
            @foreach($monthlyChart as $row)
                @php
                    $width = max(4, (abs($row['net']) / $monthlyMax) * 100);
                    $isPositive = $row['net'] >= 0;
                @endphp
                <div class="grid grid-cols-[44px_1fr_120px] items-center gap-3">
                    <div class="text-xs font-black text-slate-500">{{ $row['label'] }}</div>
                    <div class="h-4 rounded-full bg-[#e8f2fb]">
                        <div class="h-full rounded-full {{ $isPositive ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $width }}%"></div>
                    </div>
                    <div class="text-right text-xs font-black {{ $isPositive ? 'good' : 'bad' }}">{{ $rp($row['net']) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Expense Breakdown</div>
                <div class="chart-sub">Komposisi biaya terbesar</div>
            </div>
            <span class="badge">Cost</span>
        </div>
        <div class="mt-4 grid gap-4">
            @forelse($expenseBreakdown as $item)
                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-3 text-sm font-bold">
                        <span class="capitalize">{{ str_replace('_', ' ', $item['type']) }}</span>
                        <span class="text-slate-500">{{ $item['percent'] }}%</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-[#e8f2fb]">
                        <div class="h-full rounded-full bg-[#0059A7]" style="width: {{ min(100, $item['percent']) }}%"></div>
                    </div>
                    <div class="mt-1 text-xs font-bold text-slate-500">{{ $rp($item['amount']) }}</div>
                </div>
            @empty
                <div class="rounded-2xl bg-[#f3f8fc] p-6 text-center text-sm font-bold text-slate-500">Belum ada data expense.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Row 3: Operational Progress --}}
<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Project Status</div>
                <div class="chart-sub">Horizontal bar, bukan pie chart</div>
            </div>
            <a class="badge" href="{{ route('projects.index') }}">Projects</a>
        </div>
        <div class="mt-4 grid gap-3">
            @php $projectStatusMax = max(collect($projectStatus)->max('count'), 1); @endphp
            @foreach($projectStatus as $item)
                <div class="grid grid-cols-[96px_1fr_40px] items-center gap-3">
                    <span class="text-xs font-black text-slate-500">{{ $item['label'] }}</span>
                    <div class="h-3 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-[#0059A7]" style="width: {{ max(4, ($item['count'] / $projectStatusMax) * 100) }}%"></div></div>
                    <span class="text-right text-xs font-black">{{ $item['count'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Sales Funnel</div>
                <div class="chart-sub">Inquiry sampai sales order</div>
            </div>
            <a class="badge" href="{{ route('sales-crm.index') }}">Sales</a>
        </div>
        <div class="mt-4 grid gap-3">
            @foreach($salesFunnel as $item)
                <div>
                    <div class="mb-1 flex items-center justify-between text-xs font-black text-slate-500">
                        <span>{{ $item['label'] }}</span>
                        <span>{{ $item['count'] }}</span>
                    </div>
                    <div class="mx-auto h-9 rounded-xl bg-[#0059A7] text-center text-xs font-black leading-9 text-white" style="width: {{ max(18, ($item['count'] / $salesFunnelMax) * 100) }}%">
                        {{ $item['count'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Procurement Pipeline</div>
                <div class="chart-sub">Tahap procurement yang sedang berjalan</div>
            </div>
            <a class="badge" href="{{ route('procurement.index') }}">Procurement</a>
        </div>
        <div class="mt-4 grid gap-3">
            @foreach($procurementPipeline as $item)
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm font-bold">
                        <span>{{ $item['label'] }}</span>
                        <span>{{ $item['count'] }}</span>
                    </div>
                    <div class="h-4 rounded-full bg-[#e8f2fb]">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ max(4, ($item['count'] / $procurementMax) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Project Profitability</div>
                <div class="chart-sub">Top 5 margin project</div>
            </div>
            <a class="badge" href="{{ route('reports.project') }}">Margin</a>
        </div>
        <div class="mt-4 grid gap-4">
            @forelse($projectProfitability as $report)
                @php $isPositive = $report['profit_margin'] >= 0; @endphp
                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-3 text-sm font-bold">
                        <span class="truncate">{{ $report['project']->code }} - {{ $report['project']->name }}</span>
                        <span class="{{ $isPositive ? 'good' : 'bad' }}">{{ number_format($report['profit_margin'], 1) }}%</span>
                    </div>
                    <div class="h-3 rounded-full bg-[#e8f2fb]">
                        <div class="h-full rounded-full {{ $isPositive ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ max(4, (abs($report['profit_margin']) / $profitabilityMax) * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl bg-[#f3f8fc] p-6 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
            @endforelse
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Attendance Today</div>
                <div class="chart-sub">Mini stacked bar hari ini</div>
            </div>
            <span class="badge">{{ now()->format('d M') }}</span>
        </div>
        <div class="mt-5 flex h-9 overflow-hidden rounded-xl bg-[#e8f2fb]">
            @foreach($attendanceToday as $item)
                @php $width = $item['count'] > 0 ? max(5, ($item['count'] / $attendanceTotal) * 100) : 0; @endphp
                <div class="h-full {{ $loop->index === 0 ? 'bg-emerald-500' : ($loop->index === 1 ? 'bg-amber-400' : ($loop->index === 2 ? 'bg-sky-500' : 'bg-red-500')) }}" style="width: {{ $width }}%"></div>
            @endforeach
        </div>
        <div class="mt-5 grid grid-cols-2 gap-3">
            @foreach($attendanceToday as $item)
                <div class="rounded-2xl bg-[#f8fbfe] p-4">
                    <div class="text-2xl font-black">{{ $item['count'] }}</div>
                    <div class="text-xs font-black uppercase tracking-wide text-slate-500">{{ $item['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Row 4: Pending Actions --}}
<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Pending by Category</div>
                <div class="chart-sub">Area kerja yang paling banyak tertahan</div>
            </div>
            <a class="badge" href="{{ route('approvals.index') }}">Pending</a>
        </div>
        <div class="mt-4 grid gap-3">
            @foreach($pendingByCategory as $item)
                <div class="grid grid-cols-[132px_1fr_40px] items-center gap-3">
                    <span class="text-xs font-black text-slate-500">{{ $item['label'] }}</span>
                    <div class="h-3 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-amber-500" style="width: {{ max(4, ($item['count'] / $pendingMax) * 100) }}%"></div></div>
                    <span class="text-right text-xs font-black">{{ $item['count'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="mt-5 rounded-2xl bg-[#f8fbfe] p-4">
            <div class="mb-3 text-sm font-black">Task Status</div>
            <div class="grid gap-2">
                @php $taskStatusMax = max(collect($taskStatus)->max('count'), 1); @endphp
                @foreach($taskStatus as $item)
                    <div class="grid grid-cols-[88px_1fr_32px] items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">{{ $item['label'] }}</span>
                        <div class="h-2.5 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-[#0059A7]" style="width: {{ max(4, ($item['count'] / $taskStatusMax) * 100) }}%"></div></div>
                        <span class="text-right text-xs font-black">{{ $item['count'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 text-xs font-black {{ $taskOverdue > 0 ? 'bad' : 'good' }}">{{ $taskOverdue }} overdue task</div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Action Required</div>
                <div class="chart-sub">Maksimal 6 item paling mendesak</div>
            </div>
            <span class="badge">Table</span>
        </div>
        <div class="wide mt-4">
            <table>
                <thead>
                    <tr>
                        <th>Prioritas</th>
                        <th>Item</th>
                        <th>Modul</th>
                        <th>PIC</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actionItems as $item)
                        <tr>
                            <td><span class="badge {{ $item['priority'] === 'Critical' ? 'badge-rejected' : 'badge-pending' }}">{{ $item['priority'] }}</span></td>
                            <td class="font-bold">{{ $item['item'] }}</td>
                            <td>{{ $item['module'] }}</td>
                            <td>{{ $item['pic'] }}</td>
                            <td>{{ $item['due'] }}</td>
                            <td>{{ $item['status'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-slate-500">Tidak ada action mendesak.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Row 5: CMS & Public Site --}}
<section class="dashboard-chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">CMS Content Status</div>
                <div class="chart-sub">Draft, published, dan section aktif</div>
            </div>
            <a class="badge" href="{{ route('cms.index') }}">CMS</a>
        </div>
        <div class="mt-4 grid gap-3">
            @foreach($cmsStatus as $item)
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-sm font-bold">
                        <span>{{ $item['label'] }}</span>
                        <span>{{ $item['count'] }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-[#0059A7]" style="width: {{ max(4, ($item['count'] / $cmsMax) * 100) }}%"></div></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Publication Trend</div>
                <div class="chart-sub">Artikel published per bulan</div>
            </div>
            <a class="badge" href="{{ route('cms.blog.index') }}">CMS Blog</a>
        </div>
        <div class="mt-6 flex h-48 items-end gap-2">
            @foreach($publicationTrend as $row)
                <div class="flex flex-1 flex-col items-center gap-2">
                    <div class="flex h-36 w-full items-end rounded-2xl bg-[#f3f8fc] px-2 py-2">
                        <div class="w-full rounded-t-lg bg-emerald-500" style="height: {{ max(4, ($row['count'] / $publicationMax) * 100) }}%"></div>
                    </div>
                    <span class="text-[11px] font-black text-slate-500">{{ $row['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <div class="chart-title">Content Freshness</div>
                <div class="chart-sub">Umur update section Compro</div>
            </div>
            <span class="badge">{{ $lastContentUpdate ? 'Updated' : 'Empty' }}</span>
        </div>
        <div class="mt-4 grid gap-3">
            @php $freshnessMax = max($cmsFreshness->max('count'), 1); @endphp
            @foreach($cmsFreshness as $item)
                <div class="grid grid-cols-[84px_1fr_32px] items-center gap-3">
                    <span class="text-xs font-black text-slate-500">{{ $item['label'] }}</span>
                    <div class="h-3 rounded-full bg-[#e8f2fb]"><div class="h-full rounded-full bg-amber-500" style="width: {{ max(4, ($item['count'] / $freshnessMax) * 100) }}%"></div></div>
                    <span class="text-right text-xs font-black">{{ $item['count'] }}</span>
                </div>
            @endforeach
        </div>
        <div class="mt-5 rounded-2xl bg-[#f8fbfe] p-4 text-sm font-bold text-slate-500">
            Last Content Update:
            <span class="text-[#002F59]">{{ $lastContentUpdate ? \Illuminate\Support\Carbon::parse($lastContentUpdate)->diffForHumans() : '-' }}</span>
        </div>
    </div>
</section>
@endsection
