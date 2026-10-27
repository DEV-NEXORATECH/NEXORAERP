@extends('layouts.erp', ['activePage' => 'reports-reconciliation', 'pageTitle' => 'Bank Reconciliation Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Bank Reconciliation</h1>
            <p class="text-xs text-[#475569]">Rekonsiliasi bank per akun dengan book balance.</p>
        </div>
        <x-erp.toolbar-actions module="report-reconciliation" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.reconciliation') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="bank_account_id" class="filter-select">
                <option value="">Semua Bank/Kas</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected((int) request('bank_account_id') === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.reconciliation') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
            <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Bank Reconciliation Status</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Bank/Kas</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Opening</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Cash In</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Cash Out</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Book Balance</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Unreconciled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bankReconciliation as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-[#0F172A] font-bold">
                                {{ $row['bank']->name }}
                                <div class="text-[11px] text-[#475569] font-normal">{{ $row['bank']->bank_name }} {{ $row['bank']->account_number }}</div>
                            </td>
                            <td class="px-3 py-2 text-xs text-right text-[#0F172A]">{{ $rp($row['bank']->opening_balance) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-emerald-600">{{ $rp($row['income']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-rose-600">{{ $rp($row['expense']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-black text-[#0F172A]">{{ $rp($row['book_balance']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-amber-600">{{ $rp($row['unreconciled']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#475569]">Tidak ada data rekonsiliasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection