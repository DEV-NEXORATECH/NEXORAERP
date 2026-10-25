@extends('layouts.erp', ['activePage' => 'reports-aging', 'pageTitle' => 'Aging AR/AP Report'])

@section('content')
<section class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm mb-4">
    <div>
        <span>Financial Intelligence</span>
        <h1>Aging {{ strtoupper($type) }}</h1>
        <p>Laporan umur {{ $type === 'ar' ? 'piutang' : 'utang' }} berdasarkan bucket jatuh tempo.</p>
    </div>
</section>

<section class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4 report-filter section">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-header">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-icon">{!! $icon('reports') !!}</div>
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
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm"><div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm-label">{{ str_replace('_', '-', $bucket) }} hari</div><div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm-metric">{{ $rp($amount) }}</div></div>
    @endforeach
</section>

<section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm section">
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
