@extends('layouts.erp', ['activePage' => 'dashboard-procurement', 'pageTitle' => 'Procurement Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Procurement Dashboard',
        'subtitle'   => 'Pemantauan pengadaan barang & jasa, alur purchase order, penerimaan barang, dan evaluasi vendor.',
        'icon'       => 'purchase',
        'breadcrumb' => 'Procurement',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('purchase-requisitions.create-page') }}" class="inline-flex items-center gap-1.5 rounded-[8px] bg-[#0B2345] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Buat Requisition</span>
        </a>
        <a href="{{ route('purchase-orders.create-page') }}" class="inline-flex items-center gap-1.5 rounded-[8px] bg-[#EA580C] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#C2410C] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Purchase Order (PO)</span>
        </a>
        <a href="{{ route('goods-receipts.create-page') }}" class="inline-flex items-center gap-1.5 rounded-[8px] bg-[#16A34A] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#15803D] transition shadow-xs">
            <span>Penerimaan Barang</span>
        </a>
        <a href="{{ route('purchase-matches.index') }}" class="inline-flex items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-white px-3.5 py-2 text-xs font-semibold text-[#0B2345] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>3-Way Matching</span>
        </a>
        <a href="{{ route('vendors.index') }}" class="inline-flex items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-white px-3.5 py-2 text-xs font-semibold text-[#0B2345] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Daftar Vendor</span>
        </a>
    </div>

    {{-- KPI Cards (12 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#0B2345',
                    'format' => in_array($key, ['spending', 'out_bills']) ? 'currency' : (is_numeric($kpi['value']) ? 'number' : 'text'),
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Pipeline Funnel & Monthly Spending Bar --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Pipeline Funnel (5 cols) --}}
        <div class="lg:col-span-5">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Procurement Pipeline',
                'subtitle'   => 'Alur Requisition → PO → Goods Receipt → Vendor Bill',
                'chartId'    => 'chart-proc-pipeline-funnel',
            ])
        </div>

        {{-- Monthly Spending Bar Chart (7 cols) --}}
        <div class="lg:col-span-7">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Pengeluaran Pengadaan Bulanan',
                'subtitle'   => 'Akumulasi nilai purchase order yang diterbitkan per bulan',
                'badge'      => 'Purchase Orders',
                'badgeRoute' => route('purchase-orders.index'),
                'chartId'    => 'chart-proc-monthly-spending',
            ])
        </div>
    </section>

    {{-- Charts Row 2: PO Status & Vendor Spending & Requisition Status --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- PO Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Purchase Order (PO)',
                'subtitle'   => 'Draft, Approved, Received, Closed, Cancelled',
                'badge'      => 'PO',
                'badgeRoute' => route('purchase-orders.index'),
                'chartId'    => 'chart-proc-po-status-donut',
            ])
        </div>

        {{-- Vendor Spending Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Top Pengeluaran per Vendor',
                'subtitle'   => 'Vendor dengan nilai transaksi terbesar',
                'chartId'    => 'chart-proc-vendor-spending-bar',
            ])
        </div>

        {{-- Requisition Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Purchase Requisition (PR)',
                'subtitle'   => 'Draft, Pending, Approved, Rejected',
                'badge'      => 'PR',
                'badgeRoute' => route('purchase-requisitions.index'),
                'chartId'    => 'chart-proc-req-status-donut',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Goods Receipt Trend & Matching Status --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Penerimaan Barang (Goods Receipt)',
                'subtitle'   => 'Frekuensi penerimaan barang dari vendor per bulan',
                'badge'      => 'Goods Receipt',
                'badgeRoute' => route('goods-receipts.index'),
                'chartId'    => 'chart-proc-receipt-trend',
            ])
        </div>

        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status 3-Way Matching (PO vs GR vs Bill)',
                'subtitle'   => 'Pencocokan pesanan, penerimaan fisik, dan invoice tagihan',
                'badge'      => 'Purchase Matches',
                'badgeRoute' => route('purchase-matches.index'),
                'chartId'    => 'chart-proc-matching-status',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Pending Requisitions Table --}}
        <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Purchase Requisition Menunggu Respon</h3>
                <a href="{{ route('purchase-requisitions.index') }}" class="text-xs font-semibold text-[#0B2345] hover:text-[#2563EB]">Kelola PR →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#D9E2EC] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No PR</th>
                            <th class="px-3 py-2.5">Keperluan</th>
                            <th class="px-3 py-2.5">Tgl Dibutuhkan</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['pendingReqTable'] as $pr)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $pr->number }}</td>
                                <td class="px-3 py-2.5 text-[#475569] max-w-[150px] truncate">{{ $pr->purpose ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $pr->required_date ?: '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] uppercase">
                                        {{ $pr->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada PR pending.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Open Purchase Orders Table --}}
        <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Purchase Order Aktif (Open PO)</h3>
                <a href="{{ route('purchase-orders.index') }}" class="text-xs font-semibold text-[#0B2345] hover:text-[#2563EB]">Semua PO →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#D9E2EC] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No PO</th>
                            <th class="px-3 py-2.5">Vendor</th>
                            <th class="px-3 py-2.5 text-right">Nilai PO</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['openPoTable'] as $po)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $po->number }}</td>
                                <td class="px-3 py-2.5 text-[#475569]">{{ $po->vendor?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#16A34A]">Rp {{ number_format((float) $po->amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#2563EB]/10 px-2 py-0.5 text-[10px] font-bold text-[#2563EB] uppercase">
                                        {{ $po->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada PO aktif.</td></tr>
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
    // 1. Procurement Pipeline Funnel
    const pipeline = @json($data['procurementPipeline']);
    if (document.getElementById('chart-proc-pipeline-funnel')) {
        window.initNexoraChart('#chart-proc-pipeline-funnel', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Jumlah Dokumen', data: pipeline.map(p => p.count) }],
            plotOptions: {
                bar: { horizontal: true, barHeight: '55%', distributed: true, borderRadius: 3 }
            },
            colors: ['#EA580C', '#2563EB', '#0891B2', '#16A34A'],
            xaxis: { categories: pipeline.map(p => p.stage) },
            legend: { show: false }
        });
    }

    // 2. Monthly Procurement Spending Bar Chart
    const spending = @json($data['monthlySpending']);
    if (document.getElementById('chart-proc-monthly-spending')) {
        window.initNexoraChart('#chart-proc-monthly-spending', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Nilai PO (Rp)', data: spending.map(s => s.amount) }],
            colors: ['#DC2626'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: spending.map(s => s.month) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }

    // 3. PO Status Donut Chart
    const poStatus = @json($data['poStatusDonut']);
    if (document.getElementById('chart-proc-po-status-donut')) {
        window.initNexoraChart('#chart-proc-po-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(poStatus),
            labels: Object.keys(poStatus),
            colors: ['#64748B', '#2563EB', '#0891B2', '#16A34A', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 4. Vendor Spending Horizontal Bar
    const vendorSpend = @json($data['vendorSpending']);
    if (document.getElementById('chart-proc-vendor-spending-bar')) {
        window.initNexoraChart('#chart-proc-vendor-spending-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Total Belanja (Rp)', data: vendorSpend.map(v => v.amount) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#0B2345'],
            xaxis: { categories: vendorSpend.map(v => v.name) },
            yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 5. Requisition Status Donut
    const reqStatus = @json($data['reqStatusDonut']);
    if (document.getElementById('chart-proc-req-status-donut')) {
        window.initNexoraChart('#chart-proc-req-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(reqStatus),
            labels: Object.keys(reqStatus),
            colors: ['#64748B', '#EA580C', '#16A34A', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 6. Goods Receipt Trend Line Chart
    const receiptTrend = @json($data['receiptTrend']);
    if (document.getElementById('chart-proc-receipt-trend')) {
        window.initNexoraChart('#chart-proc-receipt-trend', {
            chart: { type: 'line', height: 240 },
            series: [{ name: 'Penerimaan Barang', data: receiptTrend.map(r => r.count) }],
            colors: ['#0891B2'],
            stroke: { width: 3, curve: 'smooth' },
            xaxis: { categories: receiptTrend.map(r => r.month) },
            yaxis: { labels: { formatter: val => Math.round(val) } }
        });
    }

    // 7. Matching Status Stacked Bar
    const matchStatus = @json($data['matchingStatus']);
    if (document.getElementById('chart-proc-matching-status')) {
        window.initNexoraChart('#chart-proc-matching-status', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Jumlah Dokumen', data: Object.values(matchStatus) }],
            colors: ['#16A34A', '#EA580C', '#DC2626'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3, distributed: true } },
            xaxis: { categories: Object.keys(matchStatus) },
            legend: { show: false }
        });
    }
});
</script>
@endpush
@endsection
