@extends('layouts.erp', ['activePage' => 'dashboard-cms', 'pageTitle' => 'CMS & Website Dashboard'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @include('erp.dashboard.components.header', [
        'title'      => 'CMS & Website Dashboard',
        'subtitle'   => 'Pemantauan publikasi konten blog, visibilitas section website publik, dan kesegaran materi digital.',
        'icon'       => 'cms',
        'breadcrumb' => 'CMS & Website',
        'period'     => $data['range']['period'],
    ])

    {{-- Quick Actions --}}
    <div class="dashboard-quick-actions flex flex-wrap items-center gap-2.5">
        <a href="{{ route('cms.blog.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#123A6B] transition shadow-xs">
            {!! $icon('plus') !!}
            <span>Tulis Artikel Blog</span>
        </a>
        <a href="{{ route('cms.blog.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Daftar Artikel</span>
        </a>
        <a href="{{ route('cms.sections.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>Kelola Section Landing Page</span>
        </a>
        <a href="{{ route('cms.api-settings') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-white px-3.5 py-2 text-xs font-semibold text-[#002875] hover:bg-[#F8FAFC] transition shadow-xs">
            <span>API Settings</span>
        </a>
        <a href="/" target="_blank" class="inline-flex items-center gap-1.5 rounded-md bg-[#DB2777] px-3.5 py-2 text-xs font-semibold text-white hover:bg-[#BE185D] transition shadow-xs">
            <span>Lihat Website Publik ↗</span>
        </a>
    </div>

    {{-- KPI Cards (8 cards) --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($data['kpis'] as $key => $kpi)
                @include('erp.dashboard.components.kpi-card', [
                    'label'  => $kpi['label'],
                    'value'  => $kpi['value'],
                    'color'  => $kpi['color'] ?? '#002875',
                    'format' => is_numeric($kpi['value']) ? 'number' : 'text',
                ])
            @endforeach
        </div>
    </section>

    {{-- Charts Row 1: Publication Trend Line & Content Activity Bar --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Blog Publication Trend Line (7 cols) --}}
        <div class="lg:col-span-7">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Tren Publikasi Artikel Blog (Monthly)',
                'subtitle'   => 'Jumlah artikel yang dipublikasikan per bulan',
                'badge'      => 'Blog Posts',
                'badgeRoute' => route('cms.blog.index'),
                'chartId'    => 'chart-cms-pub-trend',
            ])
        </div>

        {{-- Content Freshness Horizontal Bar (5 cols) --}}
        <div class="lg:col-span-5">
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Kesegaran Konten Website (Freshness)',
                'subtitle'   => 'Rentang waktu pembaruan terakhir section konten',
                'chartId'    => 'chart-cms-freshness-bar',
            ])
        </div>
    </section>

    {{-- Charts Row 2: Status Distributions (Blog & Sections & Activity) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- Blog Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Status Artikel Blog',
                'subtitle'   => 'Published vs Draft',
                'chartId'    => 'chart-cms-blog-status-donut',
            ])
        </div>

        {{-- Section Status Donut --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Visibilitas Section Website',
                'subtitle'   => 'Active (Tampil) vs Hidden (Disembunyikan)',
                'chartId'    => 'chart-cms-section-status-donut',
            ])
        </div>

        {{-- Content Activity Bar --}}
        <div>
            @include('erp.dashboard.components.chart-card', [
                'title'      => 'Aktivitas Konten Bulanan',
                'subtitle'   => 'Artikel Baru vs Section Diperbarui',
                'chartId'    => 'chart-cms-activity-bar',
            ])
        </div>
    </section>

    {{-- Data Tables --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Recent Blog Posts Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Artikel Blog Terkini</h3>
                <a href="{{ route('cms.blog.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Semua Artikel →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Judul</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-3 py-2.5">Dipublikasikan</th>
                            <th class="px-3 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['recentBlogPosts'] as $post)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A] max-w-[200px] truncate">{{ $post->title }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $post->status === 'published' ? 'bg-[#16A34A]/10 text-[#16A34A]' : 'bg-[#EA580C]/10 text-[#EA580C]' }}">
                                        {{ $post->status }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</td>
                                <td class="px-3 py-2.5 text-right">
                                    <a href="{{ route('cms.blog.edit', $post->id) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Belum ada artikel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recently Updated Sections Table --}}
        <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                <h3 class="text-sm font-bold tracking-tight text-[#0F172A] m-0">Section Website Diperbarui</h3>
                <a href="{{ route('cms.sections.index') }}" class="text-xs font-semibold text-[#002875] hover:text-[#2563EB]">Kelola Section →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#475569]">
                            <th class="px-3 py-2.5">Section</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-3 py-2.5">Terakhir Update</th>
                            <th class="px-3 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F5F9]">
                        @forelse($data['recentlyUpdatedSections'] as $sec)
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="px-3 py-2.5 font-semibold text-[#0F172A]">{{ $sec->title ?: $sec->key }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $sec->is_visible ? 'bg-[#16A34A]/10 text-[#16A34A]' : 'bg-[#64748B]/10 text-[#64748B]' }}">
                                        {{ $sec->is_visible ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-[#64748B]">{{ $sec->updated_at ? $sec->updated_at->diffForHumans() : '-' }}</td>
                                <td class="px-3 py-2.5 text-right">
                                    <a href="{{ route('cms.sections.edit', $sec->id) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#94A3B8]">Belum ada section.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Blog Publication Trend Line Chart
    const pubTrend = @json($data['publicationTrend']);
    if (document.getElementById('chart-cms-pub-trend')) {
        window.initNexoraChart('#chart-cms-pub-trend', {
            chart: { type: 'line', height: 260 },
            series: [{ name: 'Artikel Terbit', data: pubTrend.map(p => p.count) }],
            colors: ['#DB2777'],
            stroke: { width: 3, curve: 'smooth' },
            xaxis: { categories: pubTrend.map(p => p.month) },
            yaxis: { labels: { formatter: val => Math.round(val) } }
        });
    }

    // 2. Content Freshness Horizontal Bar
    const freshness = @json($data['contentFreshness']);
    if (document.getElementById('chart-cms-freshness-bar')) {
        window.initNexoraChart('#chart-cms-freshness-bar', {
            chart: { type: 'bar', height: 260 },
            series: [{ name: 'Jumlah Section', data: freshness.map(f => f.count) }],
            plotOptions: {
                bar: { horizontal: true, barHeight: '50%', distributed: true, borderRadius: 3 }
            },
            colors: ['#16A34A', '#2563EB', '#EA580C', '#DC2626'],
            xaxis: { labels: { formatter: val => Math.round(val) } },
            yaxis: { categories: freshness.map(f => f.label), labels: { style: { fontSize: '11px', fontWeight: 600 } } },
            legend: { show: false }
        });
    }

    // 3. Blog Status Donut
    const blogStatus = @json($data['blogStatusDonut']);
    if (document.getElementById('chart-cms-blog-status-donut')) {
        window.initNexoraChart('#chart-cms-blog-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(blogStatus),
            labels: Object.keys(blogStatus),
            colors: ['#16A34A', '#EA580C'],
            legend: { position: 'bottom' }
        });
    }

    // 4. Section Status Donut
    const secStatus = @json($data['sectionStatusDonut']);
    if (document.getElementById('chart-cms-section-status-donut')) {
        window.initNexoraChart('#chart-cms-section-status-donut', {
            chart: { type: 'donut', height: 240 },
            series: Object.values(secStatus),
            labels: Object.keys(secStatus),
            colors: ['#2563EB', '#64748B'],
            legend: { position: 'bottom' }
        });
    }

    // 5. Content Activity Bar
    const activity = @json($data['contentActivity']);
    if (document.getElementById('chart-cms-activity-bar')) {
        window.initNexoraChart('#chart-cms-activity-bar', {
            chart: { type: 'bar', height: 240 },
            series: [
                { name: 'Artikel', data: activity.map(a => a.posts) },
                { name: 'Sections', data: activity.map(a => a.sections) }
            ],
            colors: ['#DB2777', '#002875'],
            plotOptions: { bar: { columnWidth: '45%', borderRadius: 2 } },
            xaxis: { categories: activity.map(a => a.month) }
        });
    }
});
</script>
@endpush
@endsection
