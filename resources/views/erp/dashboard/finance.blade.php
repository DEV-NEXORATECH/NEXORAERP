@extends('layouts.erp', ['activePage' => 'dashboard-finance', 'pageTitle' => 'Finance & Accounting Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Finance & Accounting Dashboard',
        'subtitle'   => 'Laporan arus kas, saldo piutang & hutang, pemantauan rekonsiliasi bank, dan anggaran.',
        'icon'       => 'cashflow',
        'breadcrumb' => 'Finance & Accounting',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        <a href="{{ route('invoices.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Buat Invoice</span>
        </a>
        <a href="{{ route('payments.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#16A34A] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#15803D] transition shadow-xs">
            <span>Catat Pembayaran</span>
        </a>
        <a href="{{ route('journal-entries.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#2563EB] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#1D4ED8] transition shadow-xs">
            <span>Entry Jurnal</span>
        </a>
        <a href="{{ route('reimbursements.index-page') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Reimbursement</span>
        </a>
        <a href="{{ route('reports.profit-loss') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Laba Rugi</span>
        </a>
        <a href="{{ route('reports.balance-sheet') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Neraca</span>
        </a>
        <a href="{{ route('reports.cash-flow') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Arus Kas</span>
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
                    'format' => in_array($key, ['total_income', 'total_expense', 'net_cashflow', 'receivables', 'payables', 'pending_reimburse', 'tax_payables']) ? 'currency' : 'text',
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Cashflow Trend Area Chart & Expense Distribution Donut --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Cashflow Trend Area Chart (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Arus Kas (Cashflow Trend Area)',
                'subtitle'   => 'Cash In (Hijau), Cash Out (Merah), Net Cashflow (Biru)',
                'badge'      => 'Arus Kas',
                'badgeRoute' => route('reports.cash-flow'),
                'chartId'    => 'chart-fin-cashflow-area',
            ])
        </div>

        {{-- Expense Distribution Donut (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Kategori Beban',
                'subtitle'   => 'Komposisi pengeluaran operasional',
                'chartId'    => 'chart-fin-expense-donut',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Receivable Aging & Payable Aging --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Receivable Aging Stacked Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Aging Piutang Customer (AR Aging)',
                'subtitle'   => 'Distribusi nominal invoice belum lunas berdasarkan umur jatuh tempo',
                'badge'      => 'Aging Piutang',
                'badgeRoute' => route('reports.aging', 'ar'),
                'chartId'    => 'chart-fin-ar-aging',
            ])
        </div>

        {{-- Payable Aging Horizontal Stacked Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Aging Hutang Vendor (AP Aging)',
                'subtitle'   => 'Kewajiban pembayaran tagihan vendor berdasarkan jatuh tempo',
                'badge'      => 'Aging Hutang',
                'badgeRoute' => route('reports.aging', 'ap'),
                'chartId'    => 'chart-fin-ap-aging',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Budget vs Actual & Invoice Status & Reconciliation Status --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Budget vs Actual --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Anggaran vs Realisasi',
                'subtitle'   => 'Budget plafon vs pengeluaran',
                'badge'      => 'Budget',
                'badgeRoute' => route('reports.budget'),
                'chartId'    => 'chart-fin-budget-vs-actual',
            ])
        </div>

        {{-- Invoice Payment Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Pembayaran Invoice',
                'subtitle'   => 'Paid, Partial, Sent, Draft, Overdue',
                'badge'      => 'Invoices',
                'badgeRoute' => route('finance.index'),
                'chartId'    => 'chart-fin-invoice-status-donut',
            ])
        </div>

        {{-- Bank Reconciliation Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Rekonsiliasi Bank',
                'subtitle'   => 'Transaksi matched vs pending',
                'badge'      => 'Rekonsiliasi',
                'badgeRoute' => route('reports.reconciliation'),
                'chartId'    => 'chart-fin-recon-status-donut',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Outstanding Invoices Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Invoice Belum Lunas (Outstanding AR)</h3>
                <a href="{{ route('finance.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Kelola Invoice →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No Invoice</th>
                            <th class="px-3 py-2.5">Customer</th>
                            <th class="px-3 py-2.5 text-right">Outstanding</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['outInvoicesTable'] as $inv)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $inv->number }}</td>
                                <td class="px-3 py-2.5 text-[#475569]">{{ $inv->project?->client ?: ($inv->proposal?->client?->name ?: '-') }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#EA580C]">
                                    Rp {{ number_format(max(0, (float)$inv->amount - (float)$inv->paid_amount), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] uppercase">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Semua invoice telah lunas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Unpaid Vendor Bills Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Tagihan Vendor Belum Dibayar (AP)</h3>
                <a href="{{ route('vendor-bills.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Kelola Bills →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">No Bill</th>
                            <th class="px-3 py-2.5">Vendor</th>
                            <th class="px-3 py-2.5 text-right">Nominal</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['unpaidBillsTable'] as $bill)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-mono font-semibold text-[#0F172A]">{{ $bill->bill_number ?: ('BILL-' . $bill->id) }}</td>
                                <td class="px-3 py-2.5 text-[#475569]">{{ $bill->vendor_name }}</td>
                                <td class="px-3 py-2.5 text-right font-bold text-[#DC2626]">
                                    Rp {{ number_format((float) $bill->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#DC2626]/10 px-2 py-0.5 text-[10px] font-bold text-[#DC2626] uppercase">
                                        {{ $bill->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada tagihan vendor pending.</td></tr>
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
    // 1. Cashflow Trend Area Chart
    const cashTrend = @json($data['cashflowTrend']);
    if (document.getElementById('chart-fin-cashflow-area')) {
        window.initNexoraChart('#chart-fin-cashflow-area', {
            chart: { type: 'area', height: 280 },
            series: [
                { name: 'Cash In (Income)', data: cashTrend.map(c => c.in) },
                { name: 'Cash Out (Expense)', data: cashTrend.map(c => c.out) },
                { name: 'Net Cashflow', data: cashTrend.map(c => c.net) }
            ],
            colors: ['#16A34A', '#DC2626', '#2563EB'],
            stroke: { width: [2, 2, 3], curve: 'smooth' },
            fill: { opacity: [0.1, 0.1, 0.2] },
            xaxis: { categories: cashTrend.map(c => c.month) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            legend: { position: 'bottom' }
        });
    }

    // 2. Expense Distribution Donut
    const expDist = @json($data['expDistribution']);
    if (document.getElementById('chart-fin-expense-donut')) {
        window.initNexoraChart('#chart-fin-expense-donut', {
            chart: { type: 'donut', height: 280 },
            series: expDist.map(e => e.amount),
            labels: expDist.map(e => e.label),
            colors: ['#002875', '#DC2626', '#EA580C', '#D97706', '#7C3AED', '#0891B2'],
            legend: { position: 'bottom' }
        });
    }

    // 3. Receivable Aging Stacked Bar
    const arAging = @json($data['arAging']);
    if (document.getElementById('chart-fin-ar-aging')) {
        window.initNexoraChart('#chart-fin-ar-aging', {
            chart: { type: 'bar', height: 250 },
            series: [{ name: 'Nominal Piutang', data: arAging.map(a => a.amount) }],
            colors: ['#EA580C'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3, distributed: true } },
            xaxis: { categories: arAging.map(a => a.label) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            legend: { show: false }
        });
    }

    // 4. Payable Aging Horizontal Stacked Bar
    const apAging = @json($data['apAging']);
    if (document.getElementById('chart-fin-ap-aging')) {
        window.initNexoraChart('#chart-fin-ap-aging', {
            chart: { type: 'bar', height: 250 },
            series: [{ name: 'Nominal Hutang', data: apAging.map(a => a.amount) }],
            colors: ['#DC2626'],
            plotOptions: { bar: { horizontal: true, barHeight: '45%', borderRadius: 3, distributed: true } },
            xaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            yaxis: { categories: apAging.map(a => a.label), labels: { style: { fontSize: '11px', fontWeight: 600 } } },
            legend: { show: false }
        });
    }

    // 5. Budget vs Actual Grouped Bar
    const budgetData = @json($data['budgetComparison']);
    if (document.getElementById('chart-fin-budget-vs-actual')) {
        window.initNexoraChart('#chart-fin-budget-vs-actual', {
            chart: { type: 'bar', height: 240 },
            series: [
                { name: 'Plafon Budget', data: budgetData.map(b => b.budget) },
                { name: 'Realisasi', data: budgetData.map(b => b.actual) }
            ],
            colors: ['#002875', '#16A34A'],
            plotOptions: { bar: { columnWidth: '45%', borderRadius: 2 } },
            xaxis: { categories: budgetData.map(b => b.name) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } },
            legend: { position: 'bottom' }
        });
    }

    // 6. Invoice Payment Status Donut
    const invStatus = @json($data['invStatusDonut']);
    if (document.getElementById('chart-fin-invoice-status-donut')) {
        window.initNexoraChart('#chart-fin-invoice-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(invStatus),
            labels: Object.keys(invStatus),
            colors: ['#16A34A', '#2563EB', '#0891B2', '#64748B', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 7. Reconciliation Status Donut
    const reconStatus = @json($data['reconDonut']);
    if (document.getElementById('chart-fin-recon-status-donut')) {
        window.initNexoraChart('#chart-fin-recon-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(reconStatus),
            labels: Object.keys(reconStatus),
            colors: ['#16A34A', '#EA580C'],
            legend: { position: 'bottom' }
        });
    }
});
</script>
@endpush
@endsection
