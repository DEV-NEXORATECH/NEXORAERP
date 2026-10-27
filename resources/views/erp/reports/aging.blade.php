@extends('layouts.erp', ['activePage' => 'reports-aging', 'pageTitle' => 'Aging AR/AP Report'])

@section('content')
<div class="space-y-4">
    {{-- Card 1 — Page Header & Global Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Aging {{ strtoupper($type) }}</h1>
            <p class="text-xs text-[#475569]">Laporan umur {{ $type === 'ar' ? 'piutang' : 'utang' }} berdasarkan bucket jatuh tempo.</p>
        </div>
        <x-erp.toolbar-actions module="report-aging-{{ $type }}" :can-create="false" />
    </div>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <div class="filter-toolbar">
            <span class="text-xs font-semibold text-[#475569] mr-1">Tipe:</span>
            <a class="px-3 py-1.5 text-xs font-semibold rounded-md transition {{ $type === 'ar' ? 'bg-[#002875] text-white' : 'bg-white text-[#334155] border border-[#D1D5DB] hover:bg-[#F8FAFC]' }}" href="{{ route('reports.aging', 'ar') }}">Aging AR (Piutang)</a>
            <a class="px-3 py-1.5 text-xs font-semibold rounded-md transition {{ $type === 'ap' ? 'bg-[#002875] text-white' : 'bg-white text-[#334155] border border-[#D1D5DB] hover:bg-[#F8FAFC]' }}" href="{{ route('reports.aging', 'ap') }}">Aging AP (Utang)</a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($aging['buckets'] as $bucket => $amount)
            <div class="bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
                <div class="text-xs font-medium text-[#475569]">{{ str_replace('_', '-', $bucket) }} hari</div>
                <div class="text-lg font-bold text-[#0F172A] mt-1">{{ $rp($amount) }}</div>
            </div>
        @endforeach
    </div>

    {{-- Card 3 — Workspace: Data Table --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D1D5DB] px-4 py-3 bg-[#F8FAFC]/50">
            <h2 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">{{ $type === 'ar' ? 'Laporan Umur Piutang' : 'Laporan Umur Utang' }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D1D5DB]">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">No</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Due Date</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Days</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-left">Bucket</th>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-[#475569] uppercase tracking-wider text-right">Outstanding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($aging['items'] as $item)
                        <tr>
                            <td class="px-3 py-2 text-xs text-[#334155] text-left font-mono">{{ $item['row']->number ?? $item['row']->bill_number }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155] text-left">{{ $item['row']->due_date }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155] text-right">{{ $item['days'] }}</td>
                            <td class="px-3 py-2 text-xs text-[#334155] text-left"><span class="rounded-full px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-700">{{ $item['bucket'] }}</span></td>
                            <td class="px-3 py-2 text-xs text-[#0F172A] font-bold text-right">{{ $rp($item['outstanding']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#475569]">Tidak ada data aging.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection