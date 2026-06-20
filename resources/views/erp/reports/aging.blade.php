@extends('layouts.erp', ['activePage' => 'reports-aging', 'pageTitle' => 'Aging AR/AP Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Aging {{ strtoupper($type) }}</h1>
        <p>Laporan umur {{ $type === 'ar' ? 'piutang' : 'utang' }} berdasarkan bucket jatuh tempo.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('reports') !!}</div>
        <div>
            <h3>Pilih Tipe</h3>
            <p class="muted">Pilih antara Aging AR (Piutang) atau Aging AP (Utang).</p>
        </div>
    </div>
    <div class="filter-grid">
        <div class="filter-actions xl:col-span-6">
            <a class="button {{ $type === 'ar' ? '' : 'ghost' }}" href="{{ route('reports.aging', 'ar') }}">Aging AR (Piutang)</a>
            <a class="button {{ $type === 'ap' ? '' : 'ghost' }}" href="{{ route('reports.aging', 'ap') }}">Aging AP (Utang)</a>
        </div>
    </div>
</section>

<section class="grid cards section">
    @foreach($aging['buckets'] as $bucket => $amount)
        <div class="stat-card"><div class="stat-card-label">{{ str_replace('_', '-', $bucket) }} hari</div><div class="stat-card-metric">{{ $rp($amount) }}</div></div>
    @endforeach
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">{{ $type === 'ar' ? 'Laporan Umur Piutang' : 'Laporan Umur Utang' }}</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#d7e3ef]">
                    <th class="text-left py-2">No</th>
                    <th class="text-left py-2">Due Date</th>
                    <th class="text-right py-2">Days</th>
                    <th class="text-left py-2">Bucket</th>
                    <th class="text-right py-2">Outstanding</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aging['items'] as $item)
                    <tr class="border-b border-[#d7e3ef]/50">
                        <td class="py-2">{{ $item['row']->number ?? $item['row']->bill_number }}</td>
                        <td class="py-2">{{ $item['row']->due_date }}</td>
                        <td class="text-right py-2">{{ $item['days'] }}</td>
                        <td class="py-2"><span class="badge badge-pending">{{ $item['bucket'] }}</span></td>
                        <td class="text-right py-2">{{ $rp($item['outstanding']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
