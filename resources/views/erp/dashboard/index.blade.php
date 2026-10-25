@extends('layouts.erp', ['activePage' => 'dashboard', 'pageTitle' => 'Dashboard Utama'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Dashboard Utama',
        'subtitle'   => 'Command Center operasional ERP Nexora — ringkasan keuangan, bisnis, dan workflow sistem.',
        'icon'       => 'dashboard',
        'breadcrumb' => 'Dashboard Utama',
        'period'     => $data['range']['period'],
    ])

    {{-- Section 1 — Executive KPI Cards (8 cards in 2 rows of 4) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- 1. Total Income --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['income']['label'],
                'value'     => $data['kpis']['income']['value'],
                'icon'      => 'cashflow',
                'color'     => '#16A34A',
                'trend'     => $data['kpis']['income']['trend'],
                'route'     => $data['kpis']['income']['route'],
                'sparkline' => $data['kpis']['income']['sparkline'],
                'chartId'   => 'kpi-sparkline-income',
                'format'    => 'currency',
            ])

            {{-- 2. Total Expense --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['expense']['label'],
                'value'     => $data['kpis']['expense']['value'],
                'icon'      => 'cashflow',
                'color'     => '#DC2626',
                'trend'     => $data['kpis']['expense']['trend'],
                'route'     => $data['kpis']['expense']['route'],
                'sparkline' => $data['kpis']['expense']['sparkline'],
                'chartId'   => 'kpi-sparkline-expense',
                'format'    => 'currency',
            ])

            {{-- 3. Net Cashflow --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['net_cashflow']['label'],
                'value'     => $data['kpis']['net_cashflow']['value'],
                'icon'      => 'reports',
                'color'     => '#2563EB',
                'trend'     => $data['kpis']['net_cashflow']['trend'],
                'route'     => $data['kpis']['net_cashflow']['route'],
                'sparkline' => $data['kpis']['net_cashflow']['sparkline'],
                'chartId'   => 'kpi-sparkline-net',
                'format'    => 'currency',
            ])

            {{-- 4. Outstanding Invoices --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['outstanding_invoices']['label'],
                'value'     => $data['kpis']['outstanding_invoices']['value'],
                'icon'      => 'invoice',
                'color'     => '#EA580C',
                'trend'     => $data['kpis']['outstanding_invoices']['trend'],
                'route'     => $data['kpis']['outstanding_invoices']['route'],
                'format'    => 'currency',
            ])

            {{-- 5. Active Projects --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['active_projects']['label'],
                'value'     => $data['kpis']['active_projects']['value'],
                'icon'      => 'projects',
                'color'     => '#7C3AED',
                'trend'     => $data['kpis']['active_projects']['trend'],
                'route'     => $data['kpis']['active_projects']['route'],
                'format'    => 'number',
            ])

            {{-- 6. Pending Approvals --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['pending_approvals']['label'],
                'value'     => $data['kpis']['pending_approvals']['value'],
                'icon'      => 'audit',
                'color'     => '#D97706',
                'trend'     => $data['kpis']['pending_approvals']['trend'],
                'route'     => $data['kpis']['pending_approvals']['route'],
                'format'    => 'number',
            ])

            {{-- 7. Payroll This Month --}}
            @if(!($data['kpis']['payroll_month']['is_hidden'] ?? false))
                @include('erp.dashboard.components.kpi-card', [
                    'label'     => $data['kpis']['payroll_month']['label'],
                    'value'     => $data['kpis']['payroll_month']['value'],
                    'icon'      => 'salary',
                    'color'     => '#0891B2',
                    'trend'     => $data['kpis']['payroll_month']['trend'],
                    'route'     => $data['kpis']['payroll_month']['route'],
                    'format'    => 'currency',
                ])
            @else
                @include('erp.dashboard.components.kpi-card', [
                    'label'     => 'Total Karyawan Aktif',
                    'value'     => count($employees),
                    'icon'      => 'employees',
                    'color'     => '#0891B2',
                    'route'     => route('hr.index'),
                    'format'    => 'number',
                ])
            @endif

            {{-- 8. Pending Reimbursements --}}
            @include('erp.dashboard.components.kpi-card', [
                'label'     => $data['kpis']['pending_reimbursements']['label'],
                'value'     => $data['kpis']['pending_reimbursements']['value'],
                'icon'      => 'reimbursement',
                'color'     => '#4F46E5',
                'trend'     => $data['kpis']['pending_reimbursements']['trend'],
                'route'     => $data['kpis']['pending_reimbursements']['route'],
                'format'    => 'currency',
            ])
        </div>
    </section>

    {{-- Section 2 — Financial Performance (Layout 8:4) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Kiri (8 cols): Combo Chart (Income vs Expense vs Net Cashflow) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Kinerja Keuangan (Income vs Expense vs Net Cashflow)',
                'subtitle'   => 'Tren 6 bulan terakhir — Income (Hijau), Expense (Merah), Net Cashflow (Biru)',
                'badge'      => 'Laporan Cashflow',
                'badgeRoute' => route('reports.cash-flow'),
                'chartId'    => 'chart-financial-combo',
            ])
        </div>

        {{-- Kanan (4 cols): Expense Breakdown Donut Chart --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Pengeluaran',
                'subtitle'   => 'Berdasarkan kategori biaya transaksi',
                'badge'      => 'Finance',
                'badgeRoute' => route('cashflows.index-page'),
                'chartId'    => 'chart-expense-donut',
            ])
        </div>
    </section>

    {{-- Section 3 — Business Operations (3 cards in 1 row: 4:4:4) --}}
    <section class="grid grid-cols-1 gap-5 md:grid-cols-3">
        {{-- Card 1: Project Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Project',
                'subtitle'   => 'Distribusi status pekerjaan project',
                'badge'      => 'Projects',
                'badgeRoute' => route('projects.index'),
                'chartId'    => 'chart-project-status-donut',
            ])
        </div>

        {{-- Card 2: Sales Funnel Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Sales Funnel',
                'subtitle'   => 'Konversi Inquiry hingga Sales Order',
                'badge'      => 'CRM Pipeline',
                'badgeRoute' => route('sales.index'),
                'chartId'    => 'chart-sales-funnel-bar',
            ])
        </div>

        {{-- Card 3: Procurement Pipeline --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Procurement Pipeline',
                'subtitle'   => 'Alur Requisition ke Vendor Bill',
                'badge'      => 'Pengadaan',
                'badgeRoute' => route('purchase-orders.index'),
                'chartId'    => 'chart-procurement-pipeline-bar',
            ])
        </div>
    </section>

    {{-- Section 4 — Operational Monitoring (2 cards: 6:6) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Card 1: Attendance Today Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Presensi Hari Ini',
                'subtitle'   => 'Status kehadiran karyawan per hari ini',
                'badge'      => 'HR Presensi',
                'badgeRoute' => route('attendances.index'),
                'chartId'    => 'chart-attendance-donut',
            ])
        </div>

        {{-- Card 2: Task Overview Stacked Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Monitoring Task & Pekerjaan',
                'subtitle'   => 'Distribusi status task tim (Overdue: ' . $data['overdueTasksCount'] . ')',
                'badge'      => 'Approval & Task',
                'badgeRoute' => route('approvals.index'),
                'chartId'    => 'chart-tasks-stacked-bar',
            ])
        </div>
    </section>

    {{-- Section 5 — Action Required (Compact Table) --}}
    <section class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-[#F1F5F9] pb-3">
            <div>
                <h2 class="text-base font-bold tracking-tight text-[#0F172A] m-0">Tindakan Memerlukan Respon (Action Required)</h2>
                <p class="mt-0.5 text-xs text-[#64748B]">Daftar tagihan jatuh tempo, pengajuan pending, dan task kritikal.</p>
            </div>
            <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-1 rounded-[6px] border border-[#E5E7EB] bg-[#F8FAFC] px-3 py-1.5 text-xs font-semibold text-[#002875] hover:bg-[#F1F5F9] transition">
                <span>Pusat Approval</span>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Referensi</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Prioritas</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F1F5F9]">
                    @forelse($data['actionItems'] as $item)
                        <tr class="hover:bg-[#F8FAFC] transition">
                            <td class="px-4 py-3 font-semibold text-[#0F172A]">{{ $item['type'] }}</td>
                            <td class="px-4 py-3 font-mono font-medium text-[#475569]">{{ $item['reference'] }}</td>
                            <td class="px-4 py-3 text-[#334155] max-w-xs truncate">{{ $item['description'] }}</td>
                            <td class="px-4 py-3">
                                @if($item['priority'] === 'Critical')
                                    <span class="inline-flex rounded-full bg-[#DC2626]/10 px-2 py-0.5 text-[10px] font-bold text-[#DC2626] border border-[#DC2626]/20">CRITICAL</span>
                                @elseif($item['priority'] === 'High')
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] border border-[#EA580C]/20">HIGH</span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#2563EB]/10 px-2 py-0.5 text-[10px] font-bold text-[#2563EB] border border-[#2563EB]/20">MEDIUM</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[#64748B]">{{ $item['due_date'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-[4px] bg-[#F1F5F9] px-2 py-0.5 text-[11px] font-medium text-[#475569] uppercase">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ $item['action_url'] }}" class="inline-flex items-center gap-1 rounded-[6px] bg-[#002875] px-2.5 py-1 text-xs font-semibold text-white hover:bg-[#123A6B] transition">
                                    <span>{{ $item['action_text'] }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-xs text-[#94A3B8]">
                                Tidak ada tindakan tertunda yang memerlukan perhatian saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Section 6 — Website & Recent Activity (2 cards: 6:6) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- CMS Summary --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div>
                    <h2 class="text-base font-bold tracking-tight text-[#0F172A] m-0">Ringkasan Website & CMS Portal</h2>
                    <p class="mt-0.5 text-xs text-[#64748B]">Status publikasi artikel dan visibilitas section website.</p>
                </div>
                <a href="{{ route('cms.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#002875] hover:text-[#2563EB]">
                    <span>Kelola CMS</span>
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <div class="rounded-md border border-[#E5E7EB] bg-[#F8FAFC] p-3 text-center">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-[#16A34A]">Published Blog</span>
                    <span class="text-lg font-bold text-[#0F172A]">{{ $data['cmsSummary']['published_posts'] }}</span>
                </div>
                <div class="rounded-md border border-[#E5E7EB] bg-[#F8FAFC] p-3 text-center">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-[#EA580C]">Draft Blog</span>
                    <span class="text-lg font-bold text-[#0F172A]">{{ $data['cmsSummary']['draft_posts'] }}</span>
                </div>
                <div class="rounded-md border border-[#E5E7EB] bg-[#F8FAFC] p-3 text-center">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-[#2563EB]">Active Sections</span>
                    <span class="text-lg font-bold text-[#0F172A]">{{ $data['cmsSummary']['active_sections'] }}</span>
                </div>
                <div class="rounded-md border border-[#E5E7EB] bg-[#F8FAFC] p-3 text-center">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-[#DC2626]">Hidden Sections</span>
                    <span class="text-lg font-bold text-[#0F172A]">{{ $data['cmsSummary']['hidden_sections'] }}</span>
                </div>
            </div>

            <div class="mt-2">
                <span class="text-xs font-semibold text-[#475569]">Tren Publikasi Artikel (6 Bulan)</span>
                <div id="chart-cms-trend" class="mt-2 w-full h-36"></div>
            </div>
        </div>

        {{-- Recent Activity Timeline --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <div>
                    <h2 class="text-base font-bold tracking-tight text-[#0F172A] m-0">Aktivitas Terkini (Activity Timeline)</h2>
                    <p class="mt-0.5 text-xs text-[#64748B]">Pembaruan data invoices, proposals, dan transaksi terbaru.</p>
                </div>
                <span class="text-[11px] font-medium text-[#64748B]">Waktu Nyata</span>
            </div>

            <div class="space-y-3.5">
                @forelse($data['recentActivities'] as $act)
                    <div class="flex items-start gap-3 rounded-md p-2 hover:bg-[#F8FAFC] transition">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-[6px]" style="background-color: {{ $act['color'] }}15; color: {{ $act['color'] }};">
                            <span class="text-[10px] font-bold">{{ substr($act['badge'], 0, 2) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-semibold text-[#0F172A] truncate">{{ $act['title'] }}</span>
                                <span class="text-[10px] text-[#94A3B8] shrink-0">{{ $act['time'] }}</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] truncate mt-0.5">{{ $act['desc'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-[#94A3B8]">
                        Belum ada aktivitas tercatat hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Combo Chart: Income (Green Bar) vs Expense (Red Bar) vs Net Cashflow (Blue Line)
    const comboMonths = @json($data['comboMonths']);
    if (document.getElementById('chart-financial-combo')) {
        window.initNexoraChart('#chart-financial-combo', {
            chart: {
                type: 'line',
                height: 290
            },
            series: [
                {
                    name: 'Income',
                    type: 'column',
                    data: comboMonths.map(m => m.income)
                },
                {
                    name: 'Expense',
                    type: 'column',
                    data: comboMonths.map(m => m.expense)
                },
                {
                    name: 'Net Cashflow',
                    type: 'line',
                    data: comboMonths.map(m => m.net)
                }
            ],
            colors: ['#16A34A', '#DC2626', '#2563EB'],
            stroke: {
                width: [0, 0, 3],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    borderRadius: 3
                }
            },
            xaxis: {
                categories: comboMonths.map(m => m.label),
                labels: { style: { colors: '#64748B', fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        if (Math.abs(val) >= 1000000) return (val / 1000000).toFixed(0) + ' Jt';
                        return (val / 1000).toFixed(0) + ' Rb';
                    },
                    style: { colors: '#64748B', fontSize: '11px' }
                }
            }
        });
    }

    // 2. Expense Breakdown Donut Chart
    const expCategories = @json($data['expenseCategories']);
    const totalExpAmount = expCategories.reduce((acc, cur) => acc + (cur.amount || 0), 0);
    if (document.getElementById('chart-expense-donut')) {
        window.initNexoraChart('#chart-expense-donut', {
            chart: {
                type: 'donut',
                height: 290
            },
            series: expCategories.map(c => c.amount),
            labels: expCategories.map(c => c.name),
            colors: ['#002875', '#DC2626', '#EA580C', '#D97706', '#7C3AED', '#0891B2'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            name: { fontSize: '12px', fontWeight: 600, color: '#475569' },
                            value: {
                                fontSize: '14px',
                                fontWeight: 700,
                                color: '#0F172A',
                                formatter: val => 'Rp ' + (Math.round(val) / 1000000).toFixed(1) + 'M'
                            },
                            total: {
                                show: true,
                                label: 'Total Expense',
                                fontSize: '11px',
                                fontWeight: 600,
                                color: '#64748B',
                                formatter: () => 'Rp ' + (Math.round(totalExpAmount) / 1000000).toFixed(1) + 'M'
                            }
                        }
                    }
                }
            },
            legend: { position: 'bottom', horizontalAlign: 'center' }
        });
    }

    // 3. Project Status Donut Chart
    const projStatus = @json($data['projectStatusCounts']);
    if (document.getElementById('chart-project-status-donut')) {
        window.initNexoraChart('#chart-project-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(projStatus),
            labels: Object.keys(projStatus),
            colors: ['#2563EB', '#7C3AED', '#EA580C', '#16A34A'], // Planning: Blue, In Progress: Purple, On Hold: Orange, Completed: Green
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Projects', fontSize: '11px', color: '#64748B' }
                        }
                    }
                }
            },
            legend: { position: 'bottom' }
        });
    }

    // 4. Sales Funnel Horizontal Bar Chart
    const salesFunnel = @json($data['salesFunnel']);
    if (document.getElementById('chart-sales-funnel-bar')) {
        window.initNexoraChart('#chart-sales-funnel-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{
                name: 'Prospek & Order',
                data: salesFunnel.map(f => f.count)
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '50%',
                    distributed: true,
                    borderRadius: 3
                }
            },
            colors: ['#002875', '#4F46E5', '#2563EB', '#16A34A', '#0F766E'],
            xaxis: {
                categories: salesFunnel.map(f => f.stage),
                labels: { style: { colors: '#64748B', fontSize: '11px' } }
            },
            yaxis: { labels: { style: { colors: '#0F172A', fontSize: '11px', fontWeight: 600 } } },
            legend: { show: false }
        });
    }

    // 5. Procurement Pipeline Stacked/Horizontal Bar Chart
    const procPipeline = @json($data['procurementPipeline']);
    if (document.getElementById('chart-procurement-pipeline-bar')) {
        window.initNexoraChart('#chart-procurement-pipeline-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{
                name: 'Dokumen',
                data: procPipeline.map(p => p.count)
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '50%',
                    distributed: true,
                    borderRadius: 3
                }
            },
            colors: ['#EA580C', '#2563EB', '#0891B2', '#DC2626'],
            xaxis: {
                categories: procPipeline.map(p => p.stage),
                labels: { style: { colors: '#64748B', fontSize: '11px' } }
            },
            yaxis: { labels: { style: { colors: '#0F172A', fontSize: '11px', fontWeight: 600 } } },
            legend: { show: false }
        });
    }

    // 6. Attendance Today Donut Chart
    const attToday = @json($data['attendanceToday']);
    if (document.getElementById('chart-attendance-donut')) {
        window.initNexoraChart('#chart-attendance-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(attToday),
            labels: Object.keys(attToday),
            colors: ['#16A34A', '#EA580C', '#2563EB', '#DC2626'], // Present: Green, Late: Orange, Leave: Blue, Absent: Red
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Karyawan', fontSize: '11px', color: '#64748B' }
                        }
                    }
                }
            },
            legend: { position: 'bottom' }
        });
    }

    // 7. Task Overview Stacked Bar Chart
    const taskCounts = @json($data['taskCounts']);
    if (document.getElementById('chart-tasks-stacked-bar')) {
        window.initNexoraChart('#chart-tasks-stacked-bar', {
            chart: { type: 'bar', height: 240, stacked: true },
            series: [
                { name: 'To Do', data: [taskCounts['To Do'] || 0] },
                { name: 'In Progress', data: [taskCounts['In Progress'] || 0] },
                { name: 'Review', data: [taskCounts['Review'] || 0] },
                { name: 'Done', data: [taskCounts['Done'] || 0] },
            ],
            plotOptions: {
                bar: { horizontal: true, barHeight: '40%', borderRadius: 3 }
            },
            colors: ['#64748B', '#2563EB', '#D97706', '#16A34A'],
            xaxis: { categories: ['Total Task'] },
            legend: { position: 'bottom' }
        });
    }

    // 8. CMS Publication Trend Line Chart
    const cmsTrendData = @json($data['cmsSummary']['trend']);
    if (document.getElementById('chart-cms-trend')) {
        window.initNexoraChart('#chart-cms-trend', {
            chart: { type: 'line', height: 140, sparkline: { enabled: true } },
            series: [{ name: 'Artikel Publish', data: cmsTrendData }],
            colors: ['#DB2777'],
            stroke: { width: 2, curve: 'smooth' },
            tooltip: { fixed: { enabled: false } }
        });
    }
});
</script>
@endpush
@endsection
