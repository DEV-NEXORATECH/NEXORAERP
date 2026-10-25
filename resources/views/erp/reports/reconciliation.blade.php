@extends('layouts.erp', ['activePage' => 'reports-reconciliation', 'pageTitle' => 'Bank Reconciliation Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Bank Reconciliation</h1>
            <p class="text-xs text-gray-500">Rekonsiliasi bank per akun dengan book balance.</p>
        </div>
        <x-erp.toolbar-actions module="report-reconciliation" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
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
                <a href="{{ route('reports.reconciliation') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Bank Reconciliation Status</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Bank/Kas</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Opening</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Cash In</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Cash Out</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Book Balance</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Unreconciled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bankReconciliation as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-900 font-bold">
                                {{ $row['bank']->name }}
                                <div class="text-[11px] text-gray-500 font-normal">{{ $row['bank']->bank_name }} {{ $row['bank']->account_number }}</div>
                            </td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['bank']->opening_balance) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-emerald-600">{{ $rp($row['income']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-rose-600">{{ $rp($row['expense']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-black text-gray-900">{{ $rp($row['book_balance']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-amber-600">{{ $rp($row['unreconciled']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada data rekonsiliasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection