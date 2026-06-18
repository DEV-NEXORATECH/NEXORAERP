@extends('layouts.erp', ['activePage' => 'reports', 'pageTitle' => 'Reports'])

@section('content')

{{-- Export Actions --}}
<section class="card section">
    <div class="section-head">
        <div><h2>Export Data</h2><p class="muted">Unduh data dalam format CSV</p></div>
        <div class="flex gap-2">
            <a class="button ghost" href="{{ route('exports.cashflows') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                Export Cashflows
            </a>
            <a class="button ghost" href="{{ route('exports.project-finance') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                Export Project Finance
            </a>
        </div>
    </div>
</section>

{{-- Project Finance Cards --}}
<section class="section" id="project-finance">
    <div class="section-head">
        <h2>Project Finance Detail</h2>
        <span class="badge">{{ $projectReports->count() }} project</span>
    </div>
    <div class="grid three">
        @forelse ($projectReports as $report)
            <div class="card">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="mb-0.5">
                            <a href="{{ route('projects.show', $report['project']) }}" class="hover:text-[#0059A7]">
                                {{ $report['project']->code }} — {{ $report['project']->name }}
                            </a>
                        </h3>
                        <div class="muted">{{ $report['project']->client }}</div>
                    </div>
                    <span class="badge badge-{{ $report['project']->status }}">{{ $report['project']->status }}</span>
                </div>
                <table>
                    <tr><td class="text-slate-500">Kontrak</td><td class="text-right font-bold">{{ $rp($report['project']->contract_value) }}</td></tr>
                    <tr><td class="text-slate-500">Income</td><td class="good text-right font-bold">{{ $rp($report['summary']['income']) }}</td></tr>
                    <tr><td class="text-slate-500">Expense</td><td class="bad text-right font-bold">{{ $rp($report['summary']['expense']) }}</td></tr>
                    <tr><td class="text-slate-500">Profit/Loss</td><td class="{{ $report['summary']['balance'] >= 0 ? 'good' : 'bad' }} text-right font-black">{{ $rp($report['summary']['balance']) }}</td></tr>
                    <tr><td class="text-slate-500">Margin</td><td class="text-right font-bold">{{ number_format($report['profit_margin'], 2) }}%</td></tr>
                    <tr><td class="text-slate-500">Salary</td><td class="text-right">{{ $rp($report['salary_total']) }}</td></tr>
                    <tr><td class="text-slate-500">Reimburse</td><td class="text-right">{{ $rp($report['reimbursement_total']) }}</td></tr>
                    <tr><td class="text-slate-500">Invoice</td><td class="text-right">{{ $rp($report['invoice_total']) }}</td></tr>
                </table>
            </div>
        @empty
            <div class="col-span-full rounded-2xl bg-[#f3f8fc] p-8 text-center text-sm font-bold text-slate-500">Belum ada data project.</div>
        @endforelse
    </div>
</section>

@endsection
