@extends('layouts.erp', ['activePage' => 'reports-profit-loss', 'pageTitle' => 'Profit & Loss Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Profit & Loss (Laba Rugi)</h1>
            <p class="text-xs text-[#475569]">Laporan laba rugi dengan breakdown pendapatan dan biaya per kategori.</p>
        </div>
        <x-erp.toolbar-actions module="report-profit-loss" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.profit-loss') }}" class="filter-toolbar">
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
                <a href="{{ route('reports.profit-loss') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Revenue</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $rp($profitLoss['revenue']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Expense</div>
            <div class="text-xl font-bold text-rose-600 mt-1">{{ $rp($profitLoss['expense']) }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
            <div class="text-xs font-medium text-[#475569]">Net Profit / Loss</div>
            <div class="text-xl font-bold {{ $profitLoss['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">{{ $rp($profitLoss['net_profit']) }}</div>
        </div>
    </div>

    {{-- Two Column Detail --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
            <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
                <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Expense by Category</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($profitLoss['expense_by_category'] as $category => $amount)
                            <tr>
                                <td class="px-3 py-2 text-xs text-[#334155]">{{ $category }}</td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-[#0F172A]">{{ $rp($amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-[#94A3B8]">Belum ada expense.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
            <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
                <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Revenue by Category</h2>
            </div>
            <div class="p-3">
                <table class="table-folk w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($profitLoss['revenue_by_category'] as $category => $amount)
                            <tr>
                                <td class="px-3 py-2 text-xs text-[#334155]">{{ $category }}</td>
                                <td class="px-3 py-2 text-xs text-right font-bold text-[#0F172A]">{{ $rp($amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-[#94A3B8]">Belum ada revenue.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection