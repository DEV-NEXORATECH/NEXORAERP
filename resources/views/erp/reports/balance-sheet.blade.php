@extends('layouts.erp', ['activePage' => 'reports-balance-sheet', 'pageTitle' => 'Balance Sheet Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Balance Sheet (Neraca)</h1>
            <p class="text-xs text-gray-500">Neraca aset, liabilities, dan equity dari seluruh jurnal transaksi.</p>
        </div>
        <x-erp.toolbar-actions module="report-balance-sheet" :can-create="false" />
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach(['assets' => 'Assets', 'liabilities' => 'Liabilities', 'equity' => 'Equity'] as $key => $label)
            <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
                <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">{{ $label }}</h2>
                </div>
                <div class="p-3">
                    <table class="table-folk w-full text-left border-collapse text-xs">
                        <tbody class="divide-y divide-gray-200">
                            @forelse($balanceSheet[$key] as $row)
                                <tr>
                                    <td class="px-3 py-2 text-xs text-gray-700">{{ $row['account']->code }} - {{ $row['account']->name }}</td>
                                    <td class="px-3 py-2 text-xs text-right font-bold text-gray-900">{{ $rp($row['balance']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-gray-400">Belum ada jurnal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Balance Check Card --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs p-4">
        <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Balance Check</h2>
        <table class="table-folk w-full text-left border-collapse text-xs">
            <tbody>
                <tr>
                    <td class="px-3 py-2.5 font-semibold text-gray-700">Total Assets</td>
                    <td class="px-3 py-2.5 text-right font-black text-gray-900 text-sm">{{ $rp($balanceSheet['total_assets']) }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2.5 font-semibold text-gray-700">Liabilities + Equity</td>
                    <td class="px-3 py-2.5 text-right font-black text-gray-900 text-sm">{{ $rp($balanceSheet['total_liabilities'] + $balanceSheet['total_equity']) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection