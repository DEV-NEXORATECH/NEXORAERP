@extends('layouts.erp', ['activePage' => 'reports-general-ledger', 'pageTitle' => 'General Ledger'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">General Ledger (Buku Besar)</h1>
            <p class="text-xs text-[#475569]">Buku besar per akun dengan saldo berjalan untuk periode terpilih.</p>
        </div>
        <x-erp.toolbar-actions module="report-general-ledger" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.general-ledger') }}" class="filter-toolbar erp-filter-toolbar">
            <select name="chart_account_id" class="filter-select">
                <option value="">Semua Akun</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" @selected((int) request('chart_account_id') === $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.general-ledger') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
            <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">General Ledger Entries</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Akun</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Tanggal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">No. Jurnal</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Deskripsi</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Debit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Kredit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($paginated as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-[#334155] font-mono">{{ $row['account_code'] }} - {{ $row['account_name'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155] whitespace-nowrap">{{ $row['date'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155] font-mono">{{ $row['journal_code'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155]">{{ $row['description'] }}</td>
                            <td class="px-3 py-2 text-xs text-right text-[#0F172A]">{{ $rp($row['debit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-[#0F172A]">{{ $rp($row['credit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-[#0F172A]">{{ $rp($row['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#475569]">Tidak ada mutasi untuk filter tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection