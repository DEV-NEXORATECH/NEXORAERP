@extends('layouts.erp', ['activePage' => 'reports-cash-flow', 'pageTitle' => 'Cash Flow Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Cash Flow</h1>
            <p class="text-xs text-[#475569]">Arus kas masuk, keluar, dan net cash flow per bulan.</p>
        </div>
        <x-erp.toolbar-actions module="report-cashflow" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.cash-flow') }}" class="filter-toolbar erp-filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="project_id" class="filter-select">
                <option value="">Semua Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) request('project_id') === $project->id)>{{ $project->code }} - {{ $project->name }}</option>
                @endforeach
            </select>
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.cash-flow') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Cash In</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $rp($cashFlowStatement['operating_in']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Cash Out</div>
            <div class="text-xl font-bold text-rose-600 mt-1">{{ $rp($cashFlowStatement['operating_out']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Net Cash Flow</div>
            <div class="text-xl font-bold {{ $cashFlowStatement['net_cash'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">{{ $rp($cashFlowStatement['net_cash']) }}</div>
        </div>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
            <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Cash Flow by Month</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Bulan</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Income</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Expense</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cashFlowStatement['by_month'] as $month => $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-[#0F172A] font-semibold">{{ $month }}</td>
                            <td class="px-3 py-2 text-xs text-right text-emerald-600">{{ $rp($row['income']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-rose-600">{{ $rp($row['expense']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold {{ $row['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $rp($row['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-[#475569]">Tidak ada data arus kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection