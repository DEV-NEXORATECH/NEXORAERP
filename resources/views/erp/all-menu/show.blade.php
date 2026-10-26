@extends('layouts.erp', ['activePage' => 'modules', 'pageTitle' => $sectionName . ' Module'])

@section('content')
@php
    $hubIcons = [
        'Finance'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
        'HR'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'Procurement' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'Admin'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.6.2 1 .7 1 1.3H21a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15z"/></svg>',
        'Reports'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M3 3v18h18"/><path d="M7 16v-5M12 16V7M17 16v-8"/></svg>',
        'Sales'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M8 13h8M8 17h5"/></svg>',
        'Main'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
        'Inventory'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>',
    ];
@endphp

<div class="space-y-5">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex min-w-0 items-center gap-3.5">
            <a href="{{ route('modules.index') }}" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke Modules">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                {!! $hubIcons[$sectionName] ?? $icon('dashboard') !!}
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">ERP Module</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">{{ $sectionName }}</span>
                </div>
                <h1 class="break-words text-xl font-bold tracking-tight text-[#0F172A] sm:text-2xl">{{ $sectionName }} Hub</h1>
                <p class="mt-0.5 max-w-2xl text-xs text-[#475569]">Akses dan kelola seluruh sub menu modul operasional {{ strtolower($sectionName) }}.</p>
            </div>
        </div>

        <div class="inline-flex h-auto min-h-9 shrink-0 items-center gap-1.5 self-start rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 py-2 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Tersedia:</span>
            <span class="font-bold text-[#002875]">{{ count($items) }} Sub Modul</span>
        </div>
    </div>

    {{-- Sub Module Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($items as $item)
            <a
                href="{{ route($item['route']) }}"
                class="group flex min-h-[160px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#002875] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002875]/20"
            >
                <div>
                    <div class="flex items-center gap-3">
                        <div class="menu-module-card-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                            {!! $icon($item['icon']) !!}
                        </div>
                        <div class="min-w-0">
                            <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#002875]">
                                {{ $item['label'] }}
                            </h2>
                            <span class="text-[10px] font-medium uppercase tracking-wider text-[#475569]">
                                Fitur Aktif
                            </span>
                        </div>
                    </div>

                    <p class="mt-3 text-xs font-normal text-[#475569] line-clamp-2">
                        Buka dan kelola data operasional {{ strtolower($item['label']) }}.
                    </p>
                </div>

                <div class="mt-3 flex items-center justify-between border-t border-[#E5E7EB] pt-2.5">
                    <span class="text-xs font-semibold text-[#002875]">
                        Buka Modul
                    </span>
                    <span class="text-[#002875] transition-transform duration-200 group-hover:translate-x-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection

