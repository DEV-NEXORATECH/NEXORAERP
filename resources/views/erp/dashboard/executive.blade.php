@extends('layouts.erp', ['activePage' => 'dashboard-executive', 'pageTitle' => 'Executive Overview'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Executive Overview Dashboard',
        'subtitle'   => 'Analisis strategis performa bisnis, profitabilitas, posisi kas, dan alokasi anggaran.',
        'icon'       => 'reports',
        'breadcrumb' => 'Executive Overview',
        'period'     => $data['range']['period'],
    ])

    {{-- Section 1: KPI Cards (12 cards in 3 rows of 4) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#0B2345',
                    'trend'  => $kpi['trend'] ?? null,
                    'format' => in_array($key, ['revenue', 'expenses', 'gross_profit', 'net_profit', 'cash_position', 'receivables', 'payables']) ? 'currency' : 'text',
                ])
            @endforeach
        </div>
    </section>

    {{-- Management Alerts Banner --}}
    @if(count($data['alerts']) > 0)
        <section class="rounded-[12px] border border-[#D9E2EC] bg-[#F8FAFC] p-4 shadow-xs">
            <div class="flex items-center gap-2 mb-3">
                <span class="flex h-2.5 w-2.5 rounded-full bg-[#EA580C]"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#0F172A] m-0">Management Alerts & Exception Monitoring</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach($data['alerts'] as $alert)
                    <div class="rounded-[8px] border border-[#D9E2EC] bg-white p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#0F172A]">{{ $alert['title'] }}</span>
                            <span class="inline-flex rounded-full px-1.5 py-0.5 text-[10px] font-bold uppercase {{ $alert['type'] === 'critical' || $alert['type'] === 'danger' ? 'bg-[#DC2626]/10 text-[#DC2626]' : 'bg-[#EA580C]/10 text-[#EA580C]' }}">
                                {{ $alert['type'] }}
                            </span>
                        </div>
                        <p class="mt-1 text-[11px] text-[#64748B]">{{ $alert['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Section 2: Charts Row 1 --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Revenue & Profit Trend Combo Chart (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Revenue & Profit Trend',
                'subtitle'   => 'Revenue (Biru), Expense (Merah), Profit (Hijau)',
                'badge'      => 'Profit & Loss',
                'badgeRoute' => route('reports.profit-loss'),
                'chartId'    => 'chart-exec-combo-trend',
            ])
        </div>

        {{-- Profit Margin Trend Line Chart (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Profit Margin Trend (%)',
                'subtitle'   => 'Rasio margin bulanan vs target benchmark (25%)',
                'badge'      => 'Target 25%',
                'chartId'    => 'chart-exec-margin-trend',
            ])
        </div>
    </section>

    {{-- Section 3: Charts Row 2 --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Revenue Composition Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Komposisi Revenue',
                'subtitle'   => 'Berdasarkan kontribusi project utama',
                'chartId'    => 'chart-exec-revenue-composition',
            ])
        </div>

        {{-- Budget vs Actual Grouped Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Budget vs Realisasi',
                'subtitle'   => 'Alokasi anggaran vs pengeluaran aktual',
                'badge'      => 'Budgets',
                'badgeRoute' => route('reports.budget'),
                'chartId'    => 'chart-exec-budget-vs-actual',
            ])
        </div>

        {{-- Project Profitability Ranking Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Ranking Profit Project',
                'subtitle'   => 'Top project berdasarkan kontribusi margin',
                'chartId'    => 'chart-exec-project-ranking',
            ])
        </div>
    </section>

    {{-- Section 4: Receivables Aging Stacked Bar --}}
    <section>
        @include('erp.dashboard.components.chart-card', [
            'title'      => 'Receivables Aging Distribution',
            'subtitle'   => 'Umur piutang tagihan pelanggan (Current, 1-30, 31-60, 61-90, >90 hari)',
            'badge'      => 'Laporan Aging AR',
            'badgeRoute' => route('reports.aging', 'ar'),
            'chartId'    => 'chart-exec-ar-aging',
        ])
    </section>

    {{-- Section 5: Data Tables (Tabs / Grid) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Top 10 Clients by Revenue --}}
        <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Top Klien berdasarkan Revenue</h3>
                <span class="text-xs font-semibold text-[#475569]">{{ count($data['topClients']) }} Klien</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#D9E2EC] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Klien</th>
                            <th class="px-3 py-2.5">Invoices</th>
                            <th class="px-3 py-2.5 text-right">Total Revenue</th>
                            <th class="px-3 py-2.5 text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['topClients'] as $cl)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $cl['client_name'] }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $cl['invoices_count'] }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#16A34A]">Rp {{ number_format($cl['total_revenue'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5 text-right font-semibold text-[#EA580C]">Rp {{ number_format($cl['outstanding'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-xs text-[#94A3B8]">Belum ada data klien.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top 10 Projects by Profit --}}
        <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Top Project berdasarkan Profit</h3>
                <span class="text-xs font-semibold text-[#475569]">{{ count($data['topProjects']) }} Project</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#D9E2EC] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Project</th>
                            <th class="px-3 py-2.5 text-right">Income</th>
                            <th class="px-3 py-2.5 text-right">Expense</th>
                            <th class="px-3 py-2.5 text-right">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['topProjects'] as $pr)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $pr['project']->name }}</td>
                                <td class="px-3 py-2.5 text-right text-[#16A34A]">Rp {{ number_format($pr['income'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5 text-right text-[#DC2626]">Rp {{ number_format($pr['expense'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5 text-right font-bold {{ $pr['profit'] >= 0 ? 'text-[#2563EB]' : 'text-[#DC2626]' }}">
                                    Rp {{ number_format($pr['profit'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-xs text-[#94A3B8]">Belum ada data project.</td></tr>
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
    // 1. Revenue & Profit Trend Combo Chart
    const comboTrend = @json($data['comboTrend']);
    if (document.getElementById('chart-exec-combo-trend')) {
        window.initNexoraChart('#chart-exec-combo-trend', {
            chart: { type: 'line', height: 280 },
            series: [
                { name: 'Revenue', type: 'column', data: comboTrend.map(d => d.revenue) },
                { name: 'Expense', type: 'column', data: comboTrend.map(d => d.expense) },
                { name: 'Profit', type: 'line', data: comboTrend.map(d => d.profit) }
            ],
            colors: ['#2563EB', '#DC2626', '#16A34A'],
            stroke: { width: [0, 0, 3], curve: 'smooth' },
            plotOptions: { bar: { columnWidth: '45%', borderRadius: 3 } },
            xaxis: { categories: comboTrend.map(d => d.month) },
            yaxis: {
                labels: {
                    formatter: val => (Math.abs(val) >= 1000000 ? (val / 1000000).toFixed(0) + ' Jt' : (val / 1000).toFixed(0) + ' Rb')
                }
            }
        });
    }

    // 2. Profit Margin Trend Line Chart
    const marginTrend = @json($data['marginTrend']);
    if (document.getElementById('chart-exec-margin-trend')) {
        window.initNexoraChart('#chart-exec-margin-trend', {
            chart: { type: 'line', height: 280 },
            series: [
                { name: 'Profit Margin (%)', data: marginTrend.map(m => m.margin) },
                { name: 'Target (25%)', data: marginTrend.map(m => m.target) }
            ],
            colors: ['#7C3AED', '#EA580C'],
            stroke: { width: [3, 2], dashArray: [0, 4] },
            xaxis: { categories: marginTrend.map(m => m.month) },
            yaxis: { labels: { formatter: val => val + '%' } }
        });
    }

    // 3. Revenue Composition Donut Chart
    const revComp = @json($data['revComposition']);
    if (document.getElementById('chart-exec-revenue-composition')) {
        window.initNexoraChart('#chart-exec-revenue-composition', {
            chart: { type: 'donut', height: 250 },
            series: revComp.map(r => r.amount),
            labels: revComp.map(r => r.name),
            colors: ['#0B2345', '#2563EB', '#16A34A', '#7C3AED', '#EA580C', '#0891B2'],
            legend: { position: 'bottom' }
        });
    }

    // 4. Budget vs Actual Grouped Bar Chart
    const budgetData = @json($data['budgetComparison']);
    if (document.getElementById('chart-exec-budget-vs-actual')) {
        window.initNexoraChart('#chart-exec-budget-vs-actual', {
            chart: { type: 'bar', height: 250 },
            series: [
                { name: 'Budget', data: budgetData.map(b => b.budget) },
                { name: 'Realisasi', data: budgetData.map(b => b.actual) }
            ],
            colors: ['#0B2345', '#EA580C'],
            plotOptions: { bar: { columnWidth: '50%', borderRadius: 2 } },
            xaxis: { categories: budgetData.map(b => b.category) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }

    // 5. Project Profitability Ranking Horizontal Bar
    const ranking = @json($data['profitRanking']);
    if (document.getElementById('chart-exec-project-ranking')) {
        window.initNexoraChart('#chart-exec-project-ranking', {
            chart: { type: 'bar', height: 250 },
            series: [{ name: 'Profit', data: ranking.map(r => r.profit) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#16A34A'],
            xaxis: { categories: ranking.map(r => r.name) },
            yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 6. Receivables Aging Stacked Bar Chart
    const arAging = @json($data['arAging']);
    if (document.getElementById('chart-exec-ar-aging')) {
        window.initNexoraChart('#chart-exec-ar-aging', {
            chart: { type: 'bar', height: 220 },
            series: [{ name: 'Nominal Piutang', data: arAging.map(a => a.amount) }],
            colors: ['#EA580C'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 4, distributed: true } },
            xaxis: { categories: arAging.map(a => a.label) },
            yaxis: { labels: { formatter: val => 'Rp ' + (val / 1000000).toFixed(1) + ' Jt' } },
            legend: { show: false }
        });
    }
});
</script>
@endpush
@endsection
