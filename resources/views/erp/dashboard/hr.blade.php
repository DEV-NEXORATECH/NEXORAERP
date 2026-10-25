@extends('layouts.erp', ['activePage' => 'dashboard-hr', 'pageTitle' => 'Human Resources Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'Human Resources Dashboard',
        'subtitle'   => 'Pemantauan presensi karyawan, rekapitulasi cuti, beban payroll, dan jadwal penilaian kinerja.',
        'icon'       => 'employees',
        'breadcrumb' => 'Human Resources',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        <a href="{{ route('hr.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Presensi Harian</span>
        </a>
        <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Permohonan Cuti</span>
        </a>
        @if($can('admin', 'hr', 'finance'))
            <a href="{{ route('salaries.index-page') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
                <span>Kelola Gaji / Payroll</span>
            </a>
        @endif
        <a href="{{ route('timesheets.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Review Timesheet</span>
        </a>
        <a href="{{ route('performance-reviews.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Performance Review</span>
        </a>
    </div>

    {{-- KPI Cards (12 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @if(!($kpi['is_hidden'] ?? false))
                    @include('erp.dashboard.components.kpi-card', [
                        'label'  => $kpi['label'],
                        'value'  => $kpi['value'],
                        'color'  => $kpi['color'] ?? '#002875',
                        'format' => in_array($key, ['payroll_month', 'unpaid_salary']) ? 'currency' : (is_numeric($kpi['value']) ? 'number' : 'text'),
                    ])
                @endif
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Attendance Distribution Donut & Attendance Trend 30 Days --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Attendance Distribution (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Distribusi Presensi Hari Ini',
                'subtitle'   => 'Present, Late, Leave, Absent',
                'badge'      => 'Presensi',
                'badgeRoute' => route('attendances.index'),
                'chartId'    => 'chart-hr-att-donut',
            ])
        </div>

        {{-- Attendance Trend (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Kehadiran Karyawan (30 Hari Terakhir)',
                'subtitle'   => 'Tingkat kehadiran harian dan ketepatan waktu karyawan',
                'chartId'    => 'chart-hr-att-trend',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Department Horizontal Bar & Payroll Cost Trend --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Employees by Department --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Jumlah Karyawan per Departemen',
                'subtitle'   => 'Distribusi headcount divisi kerja',
                'chartId'    => 'chart-hr-emp-dept',
            ])
        </div>

        {{-- Payroll Cost Trend (Area Chart) --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Beban Payroll Bulanan',
                'subtitle'   => 'Total pengeluaran gaji bersih per bulan',
                'badge'      => 'Payroll',
                'badgeRoute' => route('salaries.index-page'),
                'chartId'    => 'chart-hr-payroll-trend',
            ])
        </div>
    </section>

    {{-- Charts Row 3: Leave Distribution & Skills & Timesheet Hours --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Leave Distribution Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Permohonan Cuti',
                'subtitle'   => 'Pending, Approved, Rejected',
                'badge'      => 'Cuti',
                'badgeRoute' => route('leave-requests.index'),
                'chartId'    => 'chart-hr-leave-donut',
            ])
        </div>

        {{-- Employee Skills Coverage --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Cakupan Keterampilan (Skills)',
                'subtitle'   => 'Jumlah karyawan berdasarkan kategori skill',
                'badge'      => 'Skills',
                'badgeRoute' => route('employee-skills.index'),
                'chartId'    => 'chart-hr-skills-bar',
            ])
        </div>

        {{-- Timesheet Hours Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Jam Kerja Timesheet (7 Hari)',
                'subtitle'   => 'Total jam kerja yang dicatat per hari',
                'chartId'    => 'chart-hr-timesheet-bar',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Pending Leave Requests Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Permohonan Cuti Menunggu Approval</h3>
                <a href="{{ route('leave-requests.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Semua Cuti →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Karyawan</th>
                            <th class="px-3 py-2.5">Tipe</th>
                            <th class="px-3 py-2.5">Tanggal</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['pendingLeaveTable'] as $leave)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $leave->employee?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-[#475569] capitalize">{{ $leave->type }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $leave->start_date }} s/d {{ $leave->end_date }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C]">
                                        PENDING
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada permohonan cuti pending.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Performance Review Schedule Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Jadwal Evaluasi Kinerja (Performance Review)</h3>
                <a href="{{ route('performance-reviews.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Kelola Review →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Karyawan</th>
                            <th class="px-3 py-2.5">Periode</th>
                            <th class="px-3 py-2.5">Skor / Rating</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['performanceReviewsTable'] as $rev)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $rev->employee?->name ?: '-' }}</td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $rev->review_period }}</td>
                                <td class="px-3 py-2.5 font-bold text-[#2563EB]">{{ $rev->score ?? '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#2563EB]/10 px-2 py-0.5 text-[10px] font-semibold text-[#2563EB] uppercase">
                                        {{ $rev->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Belum ada jadwal review.</td></tr>
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
    // 1. Attendance Distribution Donut
    const attDist = @json($data['attDistribution']);
    if (document.getElementById('chart-hr-att-donut')) {
        window.initNexoraChart('#chart-hr-att-donut', {
            chart: { type: 'donut', height: 260 },
            series: Object.values(attDist),
            labels: Object.keys(attDist),
            colors: ['#16A34A', '#EA580C', '#2563EB', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 2. Attendance Trend 30 Days Line Chart
    const attTrend = @json($data['attTrend']);
    if (document.getElementById('chart-hr-att-trend')) {
        window.initNexoraChart('#chart-hr-att-trend', {
            chart: { type: 'line', height: 260 },
            series: [
                { name: 'Hadir Tepat Waktu', data: attTrend.map(a => a.present) },
                { name: 'Terlambat', data: attTrend.map(a => a.late) }
            ],
            colors: ['#16A34A', '#EA580C'],
            stroke: { width: 2, curve: 'smooth' },
            xaxis: { categories: attTrend.map(a => a.date) },
            yaxis: { labels: { formatter: val => Math.round(val) } }
        });
    }

    // 3. Employee by Department Horizontal Bar
    const empDept = @json($data['empByDept']);
    if (document.getElementById('chart-hr-emp-dept')) {
        window.initNexoraChart('#chart-hr-emp-dept', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Karyawan', data: empDept.map(d => d.count) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#002875'],
            xaxis: { categories: empDept.map(d => d.name) },
            yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 4. Payroll Cost Trend Area Chart
    const payrollTrend = @json($data['payrollTrend']);
    if (document.getElementById('chart-hr-payroll-trend')) {
        window.initNexoraChart('#chart-hr-payroll-trend', {
            chart: { type: 'area', height: 260 },
            series: [{ name: 'Beban Gaji (Rp)', data: payrollTrend.map(p => p.cost) }],
            colors: ['#0891B2'],
            stroke: { width: 3, curve: 'smooth' },
            xaxis: { categories: payrollTrend.map(p => p.month) },
            yaxis: { labels: { formatter: val => (val / 1000000).toFixed(0) + ' Jt' } }
        });
    }

    // 5. Leave Distribution Donut
    const leaveDist = @json($data['leaveDistribution']);
    if (document.getElementById('chart-hr-leave-donut')) {
        window.initNexoraChart('#chart-hr-leave-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(leaveDist),
            labels: Object.keys(leaveDist),
            colors: ['#EA580C', '#16A34A', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }

    // 6. Skills Bar Chart
    const skills = @json($data['skillsCount']);
    if (document.getElementById('chart-hr-skills-bar')) {
        window.initNexoraChart('#chart-hr-skills-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Jumlah Karyawan', data: skills.map(s => s.count) }],
            plotOptions: { bar: { horizontal: true, barHeight: '45%', borderRadius: 3 } },
            colors: ['#7C3AED'],
            xaxis: { categories: skills.map(s => s.skill) }
        });
    }

    // 7. Timesheet Hours Bar Chart
    const timesheetData = @json($data['timesheetHours']);
    if (document.getElementById('chart-hr-timesheet-bar')) {
        window.initNexoraChart('#chart-hr-timesheet-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Jam Kerja', data: timesheetData.map(t => t.hours) }],
            colors: ['#2563EB'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: timesheetData.map(t => t.date) }
        });
    }
});
</script>
@endpush
@endsection
