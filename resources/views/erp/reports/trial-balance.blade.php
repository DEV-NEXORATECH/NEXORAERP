@extends('layouts.erp', ['activePage' => 'reports-trial-balance', 'pageTitle' => 'Trial Balance'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Trial Balance (Neraca Saldo)</h1>
            <p class="text-xs text-[#475569]">Neraca saldo per akun berdasarkan jurnal pada periode terpilih.</p>
        </div>
        <x-erp.toolbar-actions module="report-trial-balance" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('reports.trial-balance') }}" class="filter-toolbar">
            <input name="date_from" type="date" value="{{ request('date_from') }}" class="filter-date">
            <input name="date_to" type="date" value="{{ request('date_to') }}" class="filter-date">
            <select name="type" class="filter-select">
                <option value="">Semua Tipe Akun</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
                <a href="{{ route('reports.trial-balance') }}" class="px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
            <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Trial Balance Entries</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Kode</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Akun</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Type</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Debit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Kredit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr>
                            <td class="px-3 py-2 text-xs text-[#334155] font-mono">{{ $account['code'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#0F172A] font-medium">{{ $account['name'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#475569]">{{ $account['type'] }}</td>
                            <td class="px-3 py-2 text-xs text-right text-[#0F172A]">{{ $rp($account['debit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-[#0F172A]">{{ $rp($account['credit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-[#0F172A]">{{ $rp($account['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#475569]">Tidak ada jurnal pada periode tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection