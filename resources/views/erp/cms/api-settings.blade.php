@extends('layouts.erp', ['activePage' => 'cms-api-settings', 'pageTitle' => 'CMS API Settings'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('cms.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-[8px] border border-[#D9E2EC] bg-white text-[#475569] transition hover:border-[#0B2345] hover:text-[#0B2345]" title="Kembali ke CMS">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Headless Integration</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">REST Endpoints</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">CMS API Reference</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Dokumentasi endpoint REST API CMS yang siap dikonsumsi oleh aplikasi mobile, frontend web, atau microservices.</p>
            </div>
        </div>

        <div class="inline-flex h-9 items-center gap-1.5 self-start rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Total API:</span>
            <span class="font-bold text-[#0B2345]">{{ count($endpoints) }} Endpoint</span>
        </div>
    </div>

    {{-- Endpoints List --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D9E2EC] px-5 py-4">
            <h2 class="text-sm font-bold text-[#0F172A]">Daftar Endpoint Terdaftar</h2>
            <p class="text-xs text-[#475569]">Endpoint public dan authenticated untuk query konten.</p>
        </div>

        <div class="divide-y divide-[#D9E2EC]">
            @foreach($endpoints as $endpoint)
                <div class="flex flex-col gap-2 p-4 sm:flex-row sm:items-center sm:justify-between hover:bg-[#F8FAFC]/70 transition">
                    <div class="flex items-start sm:items-center gap-3">
                        @php
                            $method = strtoupper($endpoint['method']);
                            $methodBadge = match($method) {
                                'GET' => 'bg-[#EFF6FF] text-[#0284C7] border border-[#BAE6FD]',
                                'POST' => 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]',
                                'PUT', 'PATCH' => 'bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]',
                                'DELETE' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FCA5A5]',
                                default => 'bg-[#F1F5F9] text-[#475569] border border-[#CBD5E1]'
                            };
                        @endphp
                        <span class="inline-flex h-6 min-w-16 items-center justify-center rounded-[6px] px-2 text-[10px] font-bold tracking-wider {{ $methodBadge }}">
                            {{ $method }}
                        </span>
                        <div>
                            <h3 class="text-xs font-bold text-[#0F172A]">{{ $endpoint['name'] }}</h3>
                            <code class="font-mono text-[11px] text-[#0B2345] bg-[#E8EEF5] px-2 py-0.5 rounded-[4px]">{{ $endpoint['path'] }}</code>
                        </div>
                    </div>
                    <div class="text-[11px] text-[#475569] self-start sm:self-auto font-mono">
                        Accept: application/json
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
