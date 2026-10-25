@extends('layouts.erp', ['activePage' => 'dashboard-projects', 'pageTitle' => 'Project Management Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Project Management Dashboard',
        'subtitle'   => 'Monitoring kinerja proyek, anggaran biaya, margin laba, dan utilisasi tim.',
        'icon'       => 'projects',
        'breadcrumb' => 'Project Management',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        @if($can('admin', 'sales'))
            <a href="{{ route('projects.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
                {!! $icon('plus') !!}
                <span>Project Baru</span>
            </a>
        @endif
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Daftar Project</span>
        </a>
        <a href="{{ route('reports.project') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Laporan Profit Project</span>
        </a>
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Task Tim</span>
        </a>
        <a href="{{ route('timesheets.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Timesheet</span>
        </a>
    </div>

    {{-- KPI Cards (12 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#002875',
                    'format' => in_array($key, ['revenue', 'expenses', 'profit']) ? 'currency' : (is_numeric($kpi['value']) ? 'number' : 'text'),
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Status Donut & Monthly Revenue Area --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Project Status Donut (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Distribusi Project',
                'subtitle'   => 'Planning, In Progress, On Hold, Completed',
                'chartId'    => 'chart-proj-status-donut',
            ])
        </div>

        {{-- Monthly Project Revenue Area Chart (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Pendapatan Project (Monthly Area)',
                'subtitle'   => 'Akumulasi pemasukan per bulan dari cashflow project',
                'chartId'    => 'chart-proj-revenue-area',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Profitability Horizontal Bar & Budget vs Actual --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Project Profitability Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Profitabilitas Project (Revenue vs Expense)',
                'subtitle'   => 'Perbandingan pendapatan dan pengeluaran per project',
                'chartId'    => 'chart-proj-profitability-bar',
            ])
        </div>

        {{-- Project Budget vs Actual --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Anggaran vs Realisasi Project',
                'subtitle'   => 'Alokasi plafon budget vs total pengeluaran aktual',
                'chartId'    => 'chart-proj-budget-vs-actual',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Task Distribution & Workload & Performance Progress --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Task Distribution Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Status Task',
                'subtitle'   => 'To Do, In Progress, Review, Done',
                'chartId'    => 'chart-proj-task-donut',
            ])
        </div>

        {{-- Workload Stacked Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Beban Kerja (Jam Timesheet)',
                'subtitle'   => 'Total jam kerja dialokasikan per project',
                'chartId'    => 'chart-proj-workload-bar',
            ])
        </div>

        {{-- Project Progress List --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between">
            <div class="mb-3 border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm sm:text-base font-bold text-[#0F172A] m-0">Progress Invoice / Billing</h3>
                <p class="text-xs text-[#64748B]">Kemajuan penagihan invoice terhadap kontrak</p>
            </div>
            <div class="space-y-3.5 my-auto">
                @forelse($data['projectProgressList'] as $prog)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-[#0F172A] truncate max-w-[180px]">{{ $prog['name'] }}</span>
                            <span class="font-bold text-[#2563EB]">{{ $prog['progress'] }}%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-[#F1F5F9] overflow-hidden">
                            <div class="h-full rounded-full {{ $prog['progress'] >= 100 ? 'bg-[#16A34A]' : 'bg-[#2563EB]' }}" style="width: {{ $prog['progress'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-xs text-[#94A3B8]">Belum ada data progress.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Active Projects Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Project Berjalan (Active)</h3>
                <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Kelola Project →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Kode</th>
                            <th class="px-3 py-2.5">Nama Project</th>
                            <th class="px-3 py-2.5 text-right">Nilai Kontrak</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['activeProjects'] as $actProj)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $actProj->code }}</td>
                                <td class="px-3 py-2.5 font-medium text-[#334155]">{{ $actProj->name }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#16A34A]">Rp {{ number_format((float) $actProj->contract_value, 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#7C3AED]/10 px-2 py-0.5 text-[10px] font-bold text-[#7C3AED] uppercase">
                                        {{ $actProj->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada project aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Upcoming Deadlines --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Tenggat Waktu Project (Deadlines)</h3>
                <span class="text-xs font-semibold text-[#EA580C]">Mendekati Batas Akhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Project</th>
                            <th class="px-3 py-2.5">Batas Akhir</th>
                            <th class="px-3 py-2.5">Sisa Hari</th>
                            <th class="px-3 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['upcomingDeadlines'] as $dl)
                            @php
                                $daysLeft = today()->diffInDays(\Carbon\Carbon::parse($dl->end_date), false);
                            @endphp
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $dl->name }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $dl->end_date }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold {{ $daysLeft <= 7 ? 'bg-[#DC2626]/10 text-[#DC2626]' : 'bg-[#D97706]/10 text-[#D97706]' }}">
                                        {{ $daysLeft >= 0 ? $daysLeft . ' hari' : 'Lewat ' . abs($daysLeft) . ' hari' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <a href="{{ route('projects.show', $dl->id) }}" class="text-xs font-semibold text-[#002875] hover:underline">Buka</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada tenggat waktu kritis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Project Status Donut Chart
    const statusData = @json($data['projectStatus']);
    if (document.getElementById('chart-proj-status-donut')) {
        window.initNexoraChart('#chart-proj-status-donut', {
            chart: { type: 'donut', height: 260 },
            series: Object.values(statusData),
            labels: Object.keys(statusData),
            colors: ['#2563EB', '#7C3AED', '#EA580C', '#16A34A'],
            legend: { position: 'bottom' }
        });
    }

    // 2. Monthly Revenue Area Chart
    const revArea = @json($data['monthlyRevenueArea']);
    if (document.getElementById('chart-proj-revenue-area')) {
        window.initNexoraChart('#chart-proj-revenue-area', {
            chart: { type: 'area', height: 260 },
            series: [{ name: 'Project Revenue', data: revArea.map(r => r.revenue) }],
            colors: ['#7C3AED'],
            stroke: { width: 3, curve: 'smooth' },
            fill: { opacity: 0.15 },
            xaxis: { categories: revArea.map(r => r.month) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }

    // 3. Profitability Horizontal Bar Chart
    const profBars = @json($data['profitabilityBars']);
    if (document.getElementById('chart-proj-profitability-bar')) {
        window.initNexoraChart('#chart-proj-profitability-bar', {
            chart: { type: 'bar', height: 260 },
            series: [
                { name: 'Revenue', data: profBars.map(p => p.revenue) },
                { name: 'Expense', data: profBars.map(p => p.expense) },
                { name: 'Profit', data: profBars.map(p => p.profit) }
            ],
            colors: ['#16A34A', '#DC2626', '#2563EB'],
            plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 2 } },
            xaxis: { categories: profBars.map(p => p.name) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            legend: { position: 'bottom' }
        });
    }

    // 4. Budget vs Actual Grouped Bar Chart
    const budgetData = @json($data['budgetVsActual']);
    if (document.getElementById('chart-proj-budget-vs-actual')) {
        window.initNexoraChart('#chart-proj-budget-vs-actual', {
            chart: { type: 'bar', height: 260 },
            series: [
                { name: 'Budget Plafon', data: budgetData.map(b => b.budget) },
                { name: 'Realisasi Aktual', data: budgetData.map(b => b.actual) }
            ],
            colors: ['#002875', '#EA580C'],
            plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 2 } },
            xaxis: { categories: budgetData.map(b => b.name) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            legend: { position: 'bottom' }
        });
    }

    // 5. Task Distribution Donut
    const taskDist = @json($data['taskDistribution']);
    if (document.getElementById('chart-proj-task-donut')) {
        window.initNexoraChart('#chart-proj-task-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(taskDist),
            labels: Object.keys(taskDist),
            colors: ['#64748B', '#2563EB', '#D97706', '#16A34A'],
            legend: { position: 'bottom' }
        });
    }

    // 6. Workload Stacked Bar
    const workload = @json($data['workloadByProject']);
    if (document.getElementById('chart-proj-workload-bar')) {
        window.initNexoraChart('#chart-proj-workload-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Jam Kerja', data: workload.map(w => w.hours) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#0891B2'],
            xaxis: { categories: workload.map(w => w.project) },
            yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }
});
</script>
@endpush
@endsection
