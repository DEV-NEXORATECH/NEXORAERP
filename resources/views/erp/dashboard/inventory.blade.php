@extends('layouts.erp', ['activePage' => 'dashboard-inventory', 'pageTitle' => 'Inventory & Warehouse Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Inventory & Warehouse Dashboard',
        'subtitle'   => 'Manajemen stok gudang, mutasi barang masuk & keluar, valuasi inventaris, dan peringatan batas minimum.',
        'icon'       => 'receipt',
        'breadcrumb' => 'Inventory & Warehouse',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        <a href="{{ route('products.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Produk Baru</span>
        </a>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Daftar Produk</span>
        </a>
        <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Mutasi Stok</span>
        </a>
        <a href="{{ route('stock-adjustments.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Penyesuaian (Stock Opname)</span>
        </a>
        <a href="{{ route('warehouses.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Gudang</span>
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
                    'format' => in_array($key, ['valuation']) ? 'currency' : 'text',
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Stock by Category Donut & Stock In vs Out Grouped Bar --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Stock by Category Donut (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Stok per Kategori',
                'subtitle'   => 'Komposisi jumlah item barang per kategori',
                'badge'      => 'Kategori',
                'badgeRoute' => route('product-categories.index'),
                'chartId'    => 'chart-inv-category-donut',
            ])
        </div>

        {{-- Stock In vs Stock Out Grouped Bar (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Mutasi Barang Masuk vs Keluar (Monthly)',
                'subtitle'   => 'Stok Masuk Inbound (Hijau) vs Stok Keluar Outbound (Merah)',
                'badge'      => 'Mutasi',
                'badgeRoute' => route('stock-movements.index'),
                'chartId'    => 'chart-inv-in-vs-out',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Stock by Warehouse & Top Moving Products --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Stock by Warehouse Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Kuantitas Stok per Gudang',
                'subtitle'   => 'Total unit barang yang tersimpan di masing-masing gudang',
                'badge'      => 'Gudang',
                'badgeRoute' => route('warehouses.index'),
                'chartId'    => 'chart-inv-warehouse-bar',
            ])
        </div>

        {{-- Top Moving Products Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Produk Paling Aktif Bergerak (Top Moving)',
                'subtitle'   => 'Produk dengan frekuensi perputaran transaksi tertinggi',
                'chartId'    => 'chart-inv-top-moving-bar',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Movement Distribution Donut & Monthly Movement Trend & Adjustment Trend --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Movement Distribution Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tipe Mutasi Fisik',
                'subtitle'   => 'Inbound, Outbound, dan Adjustment',
                'chartId'    => 'chart-inv-movement-dist-donut',
            ])
        </div>

        {{-- Monthly Stock Movements Line Chart --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Frekuensi Mutasi Barang',
                'subtitle'   => 'Jumlah transaksi logistik bulanan',
                'chartId'    => 'chart-inv-movements-trend',
            ])
        </div>

        {{-- Stock Adjustment Trend Bar Chart --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Penyesuaian Stok (Opname)',
                'subtitle'   => 'Frekuensi penyesuaian inventaris per bulan',
                'badge'      => 'Penyesuaian',
                'badgeRoute' => route('stock-adjustments.index'),
                'chartId'    => 'chart-inv-adjustment-trend',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Low & Out of Stock Alert Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Peringatan Stok Menipis & Habis</h3>
                <span class="inline-flex rounded-full bg-[#DC2626]/10 px-2 py-0.5 text-[11px] font-bold text-[#DC2626]">
                    Perhatian Khusus
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Kode</th>
                            <th class="px-3 py-2.5">Nama Produk</th>
                            <th class="px-3 py-2.5 text-right">Stok Fisik</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['lowStockTable']->concat($data['outOfStockTable'])->take(6) as $item)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $item['product']->code }}</td>
                                <td class="px-3 py-2.5 text-[#334155]">{{ $item['product']->name }}</td>
                                <td class="px-3 py-2.5 text-right font-bold {{ $item['is_out'] ? 'text-[#DC2626]' : 'text-[#EA580C]' }}">
                                    {{ $item['stock'] }} {{ $item['product']->unit?->code }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $item['is_out'] ? 'bg-[#DC2626]/10 text-[#DC2626]' : 'bg-[#EA580C]/10 text-[#EA580C]' }}">
                                        {{ $item['is_out'] ? 'Habis' : 'Menipis' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Semua persediaan barang dalam batas aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Stock Movements Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Mutasi Fisik Terkini (Logistik)</h3>
                <a href="{{ route('stock-movements.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Semua Mutasi →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Produk</th>
                            <th class="px-3 py-2.5">Gudang</th>
                            <th class="px-3 py-2.5 text-right">Kuantitas</th>
                            <th class="px-3 py-2.5">Tipe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['recentMovementsTable'] as $mov)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $mov->product?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $mov->warehouse?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-right font-bold {{ $mov->type === 'in' ? 'text-[#16A34A]' : 'text-[#DC2626]' }}">
                                    {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $mov->type === 'in' ? 'bg-[#16A34A]/10 text-[#16A34A]' : 'bg-[#DC2626]/10 text-[#DC2626]' }}">
                                        {{ $mov->type }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Belum ada data mutasi logistik.</td></tr>
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
    // 1. Stock by Category Donut
    const catData = @json($data['stockByCategory']);
    if (document.getElementById('chart-inv-category-donut')) {
        window.initNexoraChart('#chart-inv-category-donut', {
            chart: { type: 'donut', height: 260 },
            series: catData.map(c => c.count),
            labels: catData.map(c => c.name),
            colors: ['#002875', '#0F766E', '#2563EB', '#16A34A', '#7C3AED', '#EA580C'],
            legend: { position: 'bottom' }
        });
    }

    // 2. Stock In vs Out Grouped Bar
    const inVsOut = @json($data['stockInVsOut']);
    if (document.getElementById('chart-inv-in-vs-out')) {
        window.initNexoraChart('#chart-inv-in-vs-out', {
            chart: { type: 'bar', height: 260 },
            series: [
                { name: 'Stok Masuk (In)', data: inVsOut.map(i => i.in) },
                { name: 'Stok Keluar (Out)', data: inVsOut.map(i => i.out) }
            ],
            colors: ['#16A34A', '#DC2626'],
            plotOptions: { bar: { columnWidth: '45%', borderRadius: 3 } },
            xaxis: { categories: inVsOut.map(i => i.month) },
            yaxis: { labels: { formatter: val => Math.round(val) } }
        });
    }

    // 3. Stock by Warehouse Horizontal Bar
    const warehouseData = @json($data['stockByWarehouse']);
    if (document.getElementById('chart-inv-warehouse-bar')) {
        window.initNexoraChart('#chart-inv-warehouse-bar', {
            chart: { type: 'bar', height: 250 },
            series: [{ name: 'Stok Unit', data: warehouseData.map(w => w.qty) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#0F766E'],
            xaxis: { labels: { formatter: val => Math.round(val) } },
            yaxis: { categories: warehouseData.map(w => w.name), labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 4. Top Moving Products Horizontal Bar
    const topMoving = @json($data['topMovingProducts']);
    if (document.getElementById('chart-inv-top-moving-bar')) {
        window.initNexoraChart('#chart-inv-top-moving-bar', {
            chart: { type: 'bar', height: 250 },
            series: [{ name: 'Frekuensi Mutasi', data: topMoving.map(t => t.count) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#2563EB'],
            xaxis: { labels: { formatter: val => Math.round(val) } },
            yaxis: { categories: topMoving.map(t => t.name), labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 5. Movement Distribution Donut
    const moveDist = @json($data['movementDistribution']);
    if (document.getElementById('chart-inv-movement-dist-donut')) {
        window.initNexoraChart('#chart-inv-movement-dist-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(moveDist),
            labels: Object.keys(moveDist),
            colors: ['#16A34A', '#DC2626', '#EA580C'],
            legend: { position: 'bottom' }
        });
    }

    // 6. Movements Trend Line Chart
    const moveTrend = @json($data['monthlyMovements']);
    if (document.getElementById('chart-inv-movements-trend')) {
        window.initNexoraChart('#chart-inv-movements-trend', {
            chart: { type: 'line', height: 240 },
            series: [{ name: 'Jumlah Mutasi', data: moveTrend.map(m => m.count) }],
            colors: ['#7C3AED'],
            stroke: { width: 3, curve: 'smooth' },
            xaxis: { categories: moveTrend.map(m => m.month) }
        });
    }

    // 7. Adjustment Trend Bar Chart
    const adjTrend = @json($data['adjustmentTrend']);
    if (document.getElementById('chart-inv-adjustment-trend')) {
        window.initNexoraChart('#chart-inv-adjustment-trend', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Penyesuaian Opname', data: adjTrend.map(a => a.count) }],
            colors: ['#EA580C'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: adjTrend.map(a => a.month) }
        });
    }
});
</script>
@endpush
@endsection
