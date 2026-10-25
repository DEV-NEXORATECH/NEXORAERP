@extends('layouts.erp', ['activePage' => 'reports-budget', 'pageTitle' => 'Budget vs Actual Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Budget vs Actual</h1>
            <p class="text-xs text-gray-500">Perbandingan anggaran dengan realisasi biaya per periode.</p>
        </div>
        <x-erp.toolbar-actions module="report-budget" :can-create="false" />
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50/50">
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Anggaran vs Realisasi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Period</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Project / Account</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Budget</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Actual</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Variance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($budgetVsActual as $row)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-700 font-mono">{{ $row['budget']->period }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                <div class="font-bold">{{ $row['budget']->project?->code ?? 'Company' }}</div>
                                <div class="text-[11px] text-gray-500">{{ $row['budget']->account?->code }} {{ $row['budget']->account?->name }}</div>
                            </td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['budget']->budget_amount) }}</td>
                            <td class="px-3 py-2 text-xs text-right text-gray-900">{{ $rp($row['actual']) }}</td>
                            <td class="px-3 py-2 text-xs text-right font-bold {{ $row['variance'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $rp($row['variance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data anggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection