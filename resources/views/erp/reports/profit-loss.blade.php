@extends('layouts.erp', ['activePage' => 'reports-profit-loss', 'pageTitle' => 'Profit & Loss Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Profit & Loss</h1>
        <p>Laporan laba rugi dengan breakdown pendapatan dan biaya per kategori.</p>
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
    <form method="get" action="{{ route('reports.profit-loss') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Project</label><select name="project_id"><option value="">Semua</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
        <div class="filter-field"><label>Bank/Kas</label><select name="bank_account_id"><option value="">Semua</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.profit-loss') }}">Reset</a>
        </div>
    </form>
</section>

<section class="report-summary-grid section">
    <div class="stat-card"><div class="stat-card-label">Revenue</div><div class="stat-card-metric good">{{ $rp($profitLoss['revenue']) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Expense</div><div class="stat-card-metric bad">{{ $rp($profitLoss['expense']) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Net Profit / Loss</div><div class="stat-card-metric {{ $profitLoss['net_profit'] >= 0 ? 'good' : 'bad' }}">{{ $rp($profitLoss['net_profit']) }}</div></div>
</section>

<section class="grid two section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <h2 class="font-bold text-lg mb-4">Revenue vs Expense</h2>
        <table class="w-full">
            <tr><td class="py-2">Revenue</td><td class="good text-right font-black py-2">{{ $rp($profitLoss['revenue']) }}</td></tr>
            <tr><td class="py-2">Expense</td><td class="bad text-right font-black py-2">{{ $rp($profitLoss['expense']) }}</td></tr>
            <tr><td class="py-2 border-t border-[#d7e3ef]">Net Profit / Loss</td><td class="{{ $profitLoss['net_profit'] >= 0 ? 'good' : 'bad' }} text-right font-black py-2 border-t border-[#d7e3ef]">{{ $rp($profitLoss['net_profit']) }}</td></tr>
        </table>
    </div>
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <h2 class="font-bold text-lg mb-4">Expense by Category</h2>
        <table class="w-full">
            @forelse($profitLoss['expense_by_category'] as $category => $amount)
                <tr><td class="py-1">{{ $category }}</td><td class="text-right font-bold py-1">{{ $rp($amount) }}</td></tr>
            @empty
                <tr><td colspan="2" class="py-8 text-center text-slate-500">Belum ada expense.</td></tr>
            @endforelse
        </table>
    </div>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Revenue by Category</h2>
    <table class="w-full">
        @forelse($profitLoss['revenue_by_category'] as $category => $amount)
            <tr><td class="py-1">{{ $category }}</td><td class="text-right font-bold py-1">{{ $rp($amount) }}</td></tr>
        @empty
            <tr><td colspan="2" class="py-8 text-center text-slate-500">Belum ada revenue.</td></tr>
        @endforelse
    </table>
</section>
@endsection
