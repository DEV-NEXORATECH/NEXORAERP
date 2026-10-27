@extends('layouts.erp', ['activePage' => 'reports-project', 'pageTitle' => 'Project Profitability Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Project Profitability</h1>
            <p class="text-xs text-[#475569]">Profitabilitas per proyek dengan margin, salary, dan invoice total.</p>
        </div>
        <x-erp.toolbar-actions module="report-project" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.project') }}" class="filter-toolbar">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.project') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Projects Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse ($projectReports as $report)
            <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs p-4">
                <h3 class="font-bold text-sm text-[#0F172A] mb-3 border-b border-gray-100 pb-2">{{ $report['project']->code }} - {{ $report['project']->name }}</h3>
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody>
                        <tr><td class="px-2.5 py-1.5 text-[#475569]">Kontrak</td><td class="px-2.5 py-1.5 text-right font-bold text-[#0F172A]">{{ $rp($report['project']->contract_value) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-[#475569]">Income</td><td class="px-2.5 py-1.5 text-emerald-600 text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-[#475569]">Expense</td><td class="px-2.5 py-1.5 text-rose-600 text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-[#475569]">Profit/Loss</td><td class="px-2.5 py-1.5 {{ $report['summary']['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                        <tr><td class="px-2.5 py-1.5 text-[#475569]">Margin</td><td class="px-2.5 py-1.5 text-right font-bold text-[#0F172A]">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                    </tbody>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-lg bg-white p-8 text-center text-xs font-semibold text-[#475569] border border-[#D1D5DB]">Belum ada data project.</div>
        @endforelse
    </div>
</div>
@endsection