@extends('layouts.erp', ['activePage' => 'reports-project', 'pageTitle' => 'Project Profitability Report'])

@section('content')
<section class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm mb-4">
    <div>
        <span>Financial Intelligence</span>
        <h1>Project Profitability</h1>
        <p>Profitabilitas per proyek dengan margin, salary, dan invoice total.</p>
    </div>
</section>

<section class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4 report-filter section">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-header">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-icon">{!! $icon('list') !!}</div>
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
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
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
            <div class="col-span-full rounded-2xl bg-[#F4F7FB] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>
@endsection
