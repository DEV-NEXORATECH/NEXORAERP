@extends('layouts.erp', ['activePage' => 'reports-project', 'pageTitle' => 'Project Profitability Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Project Profitability</h1>
        <p>Profitabilitas per proyek dengan margin, salary, dan invoice total.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('list') !!}</div>
        <div>
            <h3>Filter Report</h3>
            <p class="muted">Filter berdasarkan project.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.project') }}" class="filter-grid">
        <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.project') }}">Reset</a>
        </div>
    </form>
</section>

<section class="section" id="project-finance">
    <div class="section-head"><h2>Laporan Profitabilitas Proyek</h2><span class="badge">{{ $projectReports->count() }} project</span></div>
    <div class="grid three">
        @forelse ($projectReports as $report)
            <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
                <h3 class="font-bold text-lg mb-3">{{ $report['project']->code }} - {{ $report['project']->name }}</h3>
                <table class="w-full">
                    <tr><td class="py-1">Kontrak</td><td class="text-right font-bold py-1">{{ $rp($report['project']->contract_value) }}</td></tr>
                    <tr><td class="py-1">Income</td><td class="good text-right font-bold py-1">{{ $rp($report['summary']['income']) }}</td></tr>
                    <tr><td class="py-1">Expense</td><td class="bad text-right font-bold py-1">{{ $rp($report['summary']['expense']) }}</td></tr>
                    <tr><td class="py-1">Profit/Loss</td><td class="{{ $report['summary']['balance'] >= 0 ? 'good' : 'bad' }} text-right font-black py-1">{{ $rp($report['summary']['balance']) }}</td></tr>
                    <tr><td class="py-1">Margin</td><td class="text-right font-bold py-1">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>
@endsection
