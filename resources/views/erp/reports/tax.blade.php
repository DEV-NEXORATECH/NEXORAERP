@extends('layouts.erp', ['activePage' => 'reports-tax', 'pageTitle' => 'Tax Summary Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Tax Summary (Rekap Pajak)</h1>
            <p class="text-xs text-[#475569]">Rekapitulasi pajak output (invoice) dan input (vendor bill).</p>
        </div>
        <x-erp.toolbar-actions module="report-tax" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.tax') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.tax') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Recap & Active Rules --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
            <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
                <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Tax Recap</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-3 py-2.5 text-[#334155]">Output Tax dari Invoice</td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-600">{{ $rp($taxSummary['invoice_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-[#334155]">Input/Withholding Tax dari Vendor Bill</td>
                            <td class="px-3 py-2.5 text-right font-bold text-rose-600">{{ $rp($taxSummary['vendor_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-[#0F172A] font-bold border-t-2 border-[#D1D5DB]">Net Tax</td>
                            <td class="px-3 py-2.5 text-right font-black text-[#0F172A] border-t-2 border-[#D1D5DB]">{{ $rp($taxSummary['net_tax']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
            <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
                <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Active Tax Rules</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($taxSummary['rules'] as $rule)
                            <tr>
                                <td class="px-3 py-2 text-xs text-[#334155]">
                                    <div class="font-bold">{{ $rule->name }}</div>
                                    <div class="text-[11px] text-[#475569]">{{ $rule->tax_type }} - {{ $rule->direction }}</div>
                                </td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-[#0F172A]">{{ $rule->rate }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-[#94A3B8]">Belum ada aturan pajak aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection