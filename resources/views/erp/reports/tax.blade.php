@extends('layouts.erp', ['activePage' => 'reports-tax', 'pageTitle' => 'Tax Summary Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Tax Summary (Rekap Pajak)</h1>
            <p class="text-xs text-gray-500">Rekapitulasi pajak output (invoice) dan input (vendor bill).</p>
        </div>
        <x-erp.toolbar-actions module="report-tax" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
        <form method="get" action="{{ route('reports.tax') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.tax') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Recap & Active Rules --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Tax Recap</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-3 py-2.5 text-gray-700">Output Tax dari Invoice</td>
                            <td class="px-3 py-2.5 text-right font-bold text-emerald-600">{{ $rp($taxSummary['invoice_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-gray-700">Input/Withholding Tax dari Vendor Bill</td>
                            <td class="px-3 py-2.5 text-right font-bold text-rose-600">{{ $rp($taxSummary['vendor_tax']) }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2.5 text-gray-900 font-bold border-t-2 border-gray-300">Net Tax</td>
                            <td class="px-3 py-2.5 text-right font-black text-gray-900 border-t-2 border-gray-300">{{ $rp($taxSummary['net_tax']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Active Tax Rules</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($taxSummary['rules'] as $rule)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-700">
                                    <div class="font-bold">{{ $rule->name }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $rule->tax_type }} - {{ $rule->direction }}</div>
                                </td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rule->rate }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada aturan pajak aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection