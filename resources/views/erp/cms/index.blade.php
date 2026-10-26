@extends('layouts.erp', ['activePage' => 'cms', 'pageTitle' => 'CMS'])

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                {!! $icon('cms') !!}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Headless Content</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">API Engine</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">CMS API Control Hub</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola konten dinamis, artikel blog, dan endpoint API untuk integrasi aplikasi frontend/website eksternal.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('admin.index') }}" class="btn btn-outline inline-flex h-9 items-center gap-2 px-3.5 text-xs font-semibold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Back to Admin</span>
            </a>
            <div class="inline-flex h-9 items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A]">
                <span class="text-[#475569]">Total Item:</span>
                <span class="font-bold text-[#002875]">{{ $sectionCount + $postCount }}</span>
            </div>
        </div>
    </div>

    {{-- CMS Sub Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Section Content --}}
        <a href="{{ route('cms.sections.index') }}" class="group flex min-h-[160px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#002875] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002875]/20">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                        {!! $icon('content') !!}
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2.5 py-0.5 text-[11px] font-bold text-[#002875]">
                        {{ $sectionCount }} Sections
                    </span>
                </div>
                <div class="mt-3.5">
                    <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#002875]">Section Content</h2>
                    <p class="mt-1 text-xs text-[#475569] line-clamp-2">CRUD section konten API seperti hero banner, about, services, testimonial, dan custom block.</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-[#E5E7EB] pt-3">
                <span class="text-xs font-semibold text-[#002875]">Kelola Section</span>
                <span class="text-[#002875] transition-transform duration-200 group-hover:translate-x-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </a>

        {{-- Blog / Article --}}
        <a href="{{ route('cms.blog.index') }}" class="group flex min-h-[160px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#002875] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002875]/20">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                        {!! $icon('blog') !!}
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2.5 py-0.5 text-[11px] font-bold text-[#002875]">
                        {{ $postCount }} Artikel
                    </span>
                </div>
                <div class="mt-3.5">
                    <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#002875]">Blog & Artikel</h2>
                    <p class="mt-1 text-xs text-[#475569] line-clamp-2">Publikasi berita, wawasan industri, dan artikel dengan pemisahan status draft serta terbit.</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-[#E5E7EB] pt-3">
                <span class="text-xs font-semibold text-[#002875]">Kelola Blog</span>
                <span class="text-[#002875] transition-transform duration-200 group-hover:translate-x-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </a>

        {{-- API Settings --}}
        <a href="{{ route('cms.api-settings') }}" class="group flex min-h-[160px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#002875] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002875]/20">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                        {!! $icon('api') !!}
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#F0FDF4] border border-[#86EFAC] px-2.5 py-0.5 text-[11px] font-bold text-[#16A34A]">
                        Active Endpoints
                    </span>
                </div>
                <div class="mt-3.5">
                    <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#002875]">API Settings</h2>
                    <p class="mt-1 text-xs text-[#475569] line-clamp-2">Daftar endpoint REST API CMS terverifikasi untuk konsumsi frontend aplikasi web dan mobile.</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-[#E5E7EB] pt-3">
                <span class="text-xs font-semibold text-[#002875]">Lihat Endpoints</span>
                <span class="text-[#002875] transition-transform duration-200 group-hover:translate-x-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>
@endsection
