@extends('layouts.erp', ['activePage' => 'dashboard-sales', 'pageTitle' => 'Sales & CRM Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Sales & CRM Dashboard',
        'subtitle'   => 'Pemantauan prospek bisnis, pipeline penjualan, efektivitas penawaran, dan target sales.',
        'icon'       => 'lead',
        'breadcrumb' => 'Sales & CRM',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        <a href="{{ route('sales-inquiries.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Inquiry Baru</span>
        </a>
        <a href="{{ route('sales-leads.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#4F46E5] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#4338CA] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Lead Baru</span>
        </a>
        <a href="{{ route('proposals.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#2563EB] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#1D4ED8] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Buat Proposal</span>
        </a>
        <a href="{{ route('sales-orders.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#16A34A] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#15803D] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Sales Order</span>
        </a>
        <a href="{{ route('client-contracts.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Kontrak Klien</span>
        </a>
    </div>

    {{-- KPI Cards (11 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#002875',
                    'trend'  => $kpi['trend'] ?? null,
                    'format' => in_array($key, ['pipeline_val', 'commission']) ? 'currency' : (is_numeric($kpi['value']) ? 'number' : 'text'),
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Funnel & Sales Performance --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Sales Funnel (5 cols) --}}
        <div class="lg:col-span-5">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Sales Pipeline Funnel',
                'subtitle'   => 'Konversi dari Inquiry hingga Sales Order',
                'chartId'    => 'chart-sales-funnel',
            ])
        </div>

        {{-- Sales Performance (7 cols) --}}
        <div class="lg:col-span-7">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Performa Penjualan Bulanan',
                'subtitle'   => 'Jumlah order dan nilai transaksi order',
                'badge'      => 'Orders',
                'badgeRoute' => route('sales-orders.index'),
                'chartId'    => 'chart-sales-monthly-performance',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Status Distributions & Rankings --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Lead Status Distribution --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Status Lead',
                'subtitle'   => 'Kondisi tahapan prospek penjualan',
                'badge'      => 'Leads',
                'badgeRoute' => route('sales-leads.index'),
                'chartId'    => 'chart-lead-status-donut',
            ])
        </div>

        {{-- Proposal Status Distribution --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Status Proposal',
                'subtitle'   => 'Draft, Sent, Approved, dan Rejected',
                'badge'      => 'Proposals',
                'badgeRoute' => route('sales.index'),
                'chartId'    => 'chart-proposal-status-donut',
            ])
        </div>

        {{-- Sales by Person --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Penjualan per Tim Sales',
                'subtitle'   => 'Kontribusi nilai sales order per PIC',
                'chartId'    => 'chart-sales-by-person',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Target vs Actual & Client Contribution --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Target vs Realisasi Penjualan',
                'subtitle'   => 'Pencapaian target periode berjalan',
                'badge'      => 'Sales Targets',
                'badgeRoute' => route('sales-targets.index'),
                'chartId'    => 'chart-target-vs-actual',
            ])
        </div>

        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Top Kontribusi Klien',
                'subtitle'   => 'Klien dengan akumulasi nilai order terbesar',
                'chartId'    => 'chart-client-contribution',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Recent Sales Orders --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Sales Order Terkini</h3>
                <a href="{{ route('sales-orders.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Semua Order →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No Order</th>
                            <th class="px-3 py-2.5">Klien</th>
                            <th class="px-3 py-2.5 text-right">Nilai</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['recentOrders'] as $ord)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $ord->number }}</td>
                                <td class="px-3 py-2.5 text-[#475569]">{{ $ord->client?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#16A34A]">Rp {{ number_format((float) $ord->amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#16A34A]/10 px-2 py-0.5 text-[10px] font-semibold text-[#16A34A] uppercase">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pending Proposals --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Proposal Menunggu Respon</h3>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Semua Proposal →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No Proposal</th>
                            <th class="px-3 py-2.5">Judul / Project</th>
                            <th class="px-3 py-2.5 text-right">Nilai</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['pendingProposals'] as $prop)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $prop->number }}</td>
                                <td class="px-3 py-2.5 text-[#475569] max-w-[150px] truncate">{{ $prop->project?->name ?: $prop->title }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#2563EB]">Rp {{ number_format((float) $prop->amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-semibold text-[#EA580C] uppercase">
                                        {{ $prop->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada proposal pending.</td></tr>
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
    // 1. Sales Funnel Horizontal Bar
    const funnel = @json($data['salesFunnel']);
    if (document.getElementById('chart-sales-funnel')) {
        window.initNexoraChart('#chart-sales-funnel', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Tahapan', data: funnel.map(f => f.count) }],
            plotOptions: {
                bar: { horizontal: true, barHeight: '55%', distributed: true, borderRadius: 3 }
            },
            colors: ['#002875', '#4F46E5', '#2563EB', '#16A34A', '#0F766E'],
            xaxis: { categories: funnel.map(f => f.stage) },
            legend: { show: false }
        });
    }

    // 2. Monthly Performance Line Chart
    const perf = @json($data['monthlyPerformance']);
    if (document.getElementById('chart-sales-monthly-performance')) {
        window.initNexoraChart('#chart-sales-monthly-performance', {
            chart: { type: 'line', height: 260 },
            series: [
                { name: 'Jumlah Order', type: 'column', data: perf.map(p => p.count) },
                { name: 'Nilai Order (Rp)', type: 'line', data: perf.map(p => p.amount) }
            ],
            colors: ['#2563EB', '#16A34A'],
            stroke: { width: [0, 3], curve: 'smooth' },
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: perf.map(p => p.month) },
            yaxis: [
                { title: { text: 'Order' }, labels: { formatter: val => Math.round(val) } },
                { opposite: true, title: { text: 'Nominal' }, labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
            ]
        });
    }

    // 3. Lead Status Donut
    const leadDist = @json($data['leadStatusDistribution']);
    if (document.getElementById('chart-lead-status-donut')) {
        window.initNexoraChart('#chart-lead-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: leadDist.map(l => l.count),
            labels: leadDist.map(l => l.label),
            colors: ['#4F46E5', '#2563EB', '#0891B2', '#16A34A', '#EA580C', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 4. Proposal Status Donut
    const propStatus = @json($data['proposalStatusDistribution']);
    if (document.getElementById('chart-proposal-status-donut')) {
        window.initNexoraChart('#chart-proposal-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(propStatus),
            labels: Object.keys(propStatus),
            colors: ['#64748B', '#EA580C', '#16A34A', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 5. Sales by Person Horizontal Bar
    const salesPerson = @json($data['salesByPerson']);
    if (document.getElementById('chart-sales-by-person')) {
        window.initNexoraChart('#chart-sales-by-person', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Total Order (Rp)', data: salesPerson.map(s => s.amount) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#4F46E5'],
            xaxis: { categories: salesPerson.map(s => s.name) },
            yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 6. Target vs Actual Grouped Bar
    const targetData = @json($data['targetVsActual']);
    if (document.getElementById('chart-target-vs-actual')) {
        window.initNexoraChart('#chart-target-vs-actual', {
            chart: { type: 'bar', height: 240 },
            series: [
                { name: 'Target', data: targetData.map(t => t.target) },
                { name: 'Realisasi', data: targetData.map(t => t.achieved) }
            ],
            colors: ['#002875', '#16A34A'],
            plotOptions: { bar: { columnWidth: '45%', borderRadius: 2 } },
            xaxis: { categories: targetData.map(t => t.period) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }

    // 7. Client Contribution Bar
    const clientContrib = @json($data['clientContribution']);
    if (document.getElementById('chart-client-contribution')) {
        window.initNexoraChart('#chart-client-contribution', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Order Value', data: clientContrib.map(c => c.amount) }],
            colors: ['#2563EB'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: clientContrib.map(c => c.client) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }
});
</script>
@endpush
@endsection
