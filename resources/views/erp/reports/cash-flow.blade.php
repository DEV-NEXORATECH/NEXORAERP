@extends('layouts.erp', ['activePage' => 'reports-cash-flow', 'pageTitle' => 'Cash Flow Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Cash Flow</h1>
        <p>Arus kas masuk, keluar, dan net cash flow per bulan.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('reports') !!}</div>
        <div>
            <h3>Filter Report</h3>
            <p class="muted">Filter berdasarkan periode, project, dan bank/kas.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.cash-flow') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
        <div class="filter-field"><label>Bank/Kas</label><select name="bank_account_id"><option value="">Semua</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.cash-flow') }}">Reset</a>
        </div>
    </form>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Cash Flow Summary</h2>
    <table class="w-full">
        <tr><td class="py-2">Cash In</td><td class="good text-right font-black py-2">{{ $rp($cashFlowStatement['operating_in']) }}</td></tr>
        <tr><td class="py-2">Cash Out</td><td class="bad text-right font-black py-2">{{ $rp($cashFlowStatement['operating_out']) }}</td></tr>
        <tr><td class="py-2 border-t border-[#d7e3ef]">Net Cash Flow</td><td class="{{ $cashFlowStatement['net_cash'] >= 0 ? 'good' : 'bad' }} text-right font-black py-2 border-t border-[#d7e3ef]">{{ $rp($cashFlowStatement['net_cash']) }}</td></tr>
    </table>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Cash Flow by Month</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#d7e3ef]">
                    <th class="text-left py-2">Bulan</th>
                    <th class="text-right py-2">Income</th>
                    <th class="text-right py-2">Expense</th>
                    <th class="text-right py-2">Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cashFlowStatement['by_month'] as $month => $row)
                    <tr class="border-b border-[#d7e3ef]/50">
                        <td class="py-2">{{ $month }}</td>
                        <td class="text-right py-2">{{ $rp($row['income']) }}</td>
                        <td class="text-right py-2">{{ $rp($row['expense']) }}</td>
                        <td class="text-right py-2 font-bold {{ $row['balance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($row['balance']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
