@extends('layouts.erp', ['activePage' => 'reports-tax', 'pageTitle' => 'Tax Summary Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Tax Summary</h1>
        <p>Rekapitulasi pajak output (invoice) dan input (vendor bill).</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('reports') !!}</div>
        <div>
            <h3>Filter Report</h3>
            <p class="muted">Filter berdasarkan periode.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.tax') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.tax') }}">Reset</a>
        </div>
    </form>
</section>

<section class="grid two section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <h2 class="font-bold text-lg mb-4">Tax Recap</h2>
        <table class="w-full">
            <tr><td class="py-2">Output Tax dari Invoice</td><td class="text-right font-bold py-2">{{ $rp($taxSummary['invoice_tax']) }}</td></tr>
            <tr><td class="py-2">Input/Withholding Tax dari Vendor Bill</td><td class="text-right font-bold py-2">{{ $rp($taxSummary['vendor_tax']) }}</td></tr>
            <tr><td class="py-2 border-t border-[#d7e3ef]">Net Tax</td><td class="text-right font-black py-2 border-t border-[#d7e3ef]">{{ $rp($taxSummary['net_tax']) }}</td></tr>
        </table>
    </div>
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <h2 class="font-bold text-lg mb-4">Active Tax Rules</h2>
        <table class="w-full">
            @foreach($taxSummary['rules'] as $rule)
                <tr><td class="py-1">{{ $rule->name }}<br><span class="muted text-xs">{{ $rule->tax_type }} - {{ $rule->direction }}</span></td><td class="text-right py-1">{{ $rule->rate }}%</td></tr>
            @endforeach
        </table>
    </div>
</section>
@endsection
