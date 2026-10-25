@extends('layouts.erp', ['activePage' => 'dashboard-workspace', 'pageTitle' => 'My Workspace'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'My Workspace',
        'subtitle'   => 'Ruang kerja pribadi Anda — tugas harian, presensi, pengajuan cuti & reimbursement, dan jam kerja.',
        'icon'       => 'users',
        'breadcrumb' => 'My Workspace',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Buat Task Baru</span>
        </a>
        <a href="{{ route('timesheets.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#2563EB] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#1D4ED8] transition shadow-xs">
            <span>Catat Timesheet</span>
        </a>
        <a href="{{ route('leave-requests.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Ajukan Cuti</span>
        </a>
        <a href="{{ route('reimbursements.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Klaim Reimbursement</span>
        </a>
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Pusat Approval</span>
        </a>
    </div>

    {{-- KPI Cards (8 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#002875',
                    'format' => is_numeric($kpi['value']) ? 'number' : 'text',
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: My Task Status Donut & My Productivity Bar --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Task Status Donut (4 cols) --}}
        <div class="lg:col-span-4">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Task Saya',
                'subtitle'   => 'To Do, In Progress, Review, Done',
                'badge'      => 'Tugas Saya',
                'badgeRoute' => route('approvals.index'),
                'chartId'    => 'chart-ws-task-status-donut',
            ])
        </div>

        {{-- Productivity Bar Chart (8 cols) --}}
        <div class="lg:col-span-8">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Produktivitas Penyelesaian Tugas (Monthly)',
                'subtitle'   => 'Jumlah task yang berhasil diselesaikan per bulan',
                'chartId'    => 'chart-ws-productivity-bar',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Timesheet Area & Workload Horizontal Bar & Requests Donut --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Timesheet Area Chart --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Jam Kerja Tercatat (Timesheet)',
                'subtitle'   => 'Akumulasi jam kerja per bulan',
                'badge'      => 'Timesheet',
                'badgeRoute' => route('timesheets.index'),
                'chartId'    => 'chart-ws-timesheet-area',
            ])
        </div>

        {{-- Workload by Project Horizontal Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Alokasi Project (Workload)',
                'subtitle'   => 'Jumlah tugas per project aktif',
                'chartId'    => 'chart-ws-workload-bar',
            ])
        </div>

        {{-- Requests Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Pengajuan Saya',
                'subtitle'   => 'Klaim reimbursement & permohonan cuti',
                'chartId'    => 'chart-ws-requests-donut',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- My Tasks Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Daftar Tugas Saya</h3>
                <a href="{{ route('approvals.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Judul Tugas</th>
                            <th class="px-3 py-2.5">Prioritas</th>
                            <th class="px-3 py-2.5">Tenggat</th>
                            <th class="px-3 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['myTasksTable'] as $task)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A] max-w-[200px] truncate">{{ $task->title }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $task->priority === 'critical' ? 'bg-[#DC2626]/10 text-[#DC2626]' : ($task->priority === 'high' ? 'bg-[#EA580C]/10 text-[#EA580C]' : 'bg-[#2563EB]/10 text-[#2563EB]') }}">
                                        {{ $task->priority ?: 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $task->due_date ?: '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-[4px] bg-[#F1F5F9] px-2 py-0.5 text-[10px] font-medium text-[#475569] uppercase">
                                        {{ $task->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Tidak ada tugas aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Upcoming Deadlines Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Tenggat Waktu Mendatang (Deadlines)</h3>
                <span class="text-xs font-semibold text-[#EA580C]">Prioritas Waktu</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Tugas</th>
                            <th class="px-3 py-2.5">Jatuh Tempo</th>
                            <th class="px-3 py-2.5">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['myUpcomingDeadlines'] as $dl)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A] max-w-[200px] truncate">{{ $dl->title }}</td>
                                <td class="px-3 py-2.5 text-[#DC2626] font-semibold">{{ $dl->due_date }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full bg-[#EA580C]/10 px-2 py-0.5 text-[10px] font-bold text-[#EA580C] uppercase">
                                        {{ $dl->priority ?: 'Normal' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-3 py-4 text-center text-[#94A3B8]">Semua tenggat waktu aman.</td></tr>
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
    // 1. Task Status Donut
    const taskStatus = @json($data['myTaskStatusDonut']);
    if (document.getElementById('chart-ws-task-status-donut')) {
        window.initNexoraChart('#chart-ws-task-status-donut', {
            chart: { type: 'donut', height: 260 },
            series: Object.values(taskStatus),
            labels: Object.keys(taskStatus),
            colors: ['#64748B', '#2563EB', '#D97706', '#16A34A'],
            legend: { position: 'bottom' }
        });
    }

    // 2. Productivity Bar Chart
    const prod = @json($data['myProductivity']);
    if (document.getElementById('chart-ws-productivity-bar')) {
        window.initNexoraChart('#chart-ws-productivity-bar', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Task Selesai', data: prod.map(p => p.completed) }],
            colors: ['#16A34A'],
            plotOptions: { bar: { columnWidth: '40%', borderRadius: 3 } },
            xaxis: { categories: prod.map(p => p.month) },
            yaxis: { labels: { formatter: val => Math.round(val) } }
        });
    }

    // 3. Timesheet Area Chart
    const ts = @json($data['myTimesheetArea']);
    if (document.getElementById('chart-ws-timesheet-area')) {
        window.initNexoraChart('#chart-ws-timesheet-area', {
            chart: { type: 'area', height: 240 },
            series: [{ name: 'Jam Kerja', data: ts.map(t => t.hours) }],
            colors: ['#0891B2'],
            stroke: { width: 3, curve: 'smooth' },
            xaxis: { categories: ts.map(t => t.month) }
        });
    }

    // 4. Workload Horizontal Bar
    const workload = @json($data['myWorkload']);
    if (document.getElementById('chart-ws-workload-bar')) {
        window.initNexoraChart('#chart-ws-workload-bar', {
            chart: { type: 'bar', height: 240 },
            series: [{ name: 'Task', data: workload.map(w => w.tasks) }],
            plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 3 } },
            colors: ['#4F46E5'],
            xaxis: { labels: { formatter: val => Math.round(val) } },
            yaxis: { categories: workload.map(w => w.name), labels: { style: { fontSize: '11px', fontWeight: 600 } } }
        });
    }

    // 5. Requests Donut
    const reqStatus = @json($data['myRequestsDonut']);
    if (document.getElementById('chart-ws-requests-donut')) {
        window.initNexoraChart('#chart-ws-requests-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(reqStatus),
            labels: Object.keys(reqStatus),
            colors: ['#EA580C', '#16A34A', '#DC2626'],
            legend: { position: 'bottom' }
        });
    }
});
</script>
@endpush
@endsection
