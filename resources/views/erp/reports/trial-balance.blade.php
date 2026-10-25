@extends('layouts.erp', ['activePage' => 'reports-trial-balance', 'pageTitle' => 'Trial Balance'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Trial Balance (Neraca Saldo)</h1>
            <p class="text-xs text-gray-500">Neraca saldo per akun berdasarkan jurnal pada periode terpilih.</p>
        </div>
        <x-erp.toolbar-actions module="report-trial-balance" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-card">
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
                <a href="{{ route('reports.trial-balance') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Trial Balance Entries</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Kode</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Akun</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Type</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Debit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Kredit</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $account['code'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-medium">{{ $account['name'] }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">{{ $account['type'] }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($account['debit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($account['credit']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($account['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">Tidak ada jurnal pada periode tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection