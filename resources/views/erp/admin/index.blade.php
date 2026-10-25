@extends('layouts.erp', ['activePage' => 'company-settings', 'pageTitle' => 'Settings Admin'])

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                {!! $icon('settings') !!}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">System Administration</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Config Hub</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Settings Admin</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola konfigurasi sistem enterprise, manajemen hak akses pengguna, master data, audit trail, dan backup.</p>
            </div>
        </div>

        <div class="inline-flex h-9 items-center gap-1.5 self-start rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Tersedia:</span>
            <span class="font-bold text-[#002875]">{{ count($items) }} Pengaturan</span>
        </div>
    </div>

    {{-- Sub Hub Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($items as $item)
            <a
                href="{{ route($item['route']) }}"
                class="group flex min-h-[160px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#002875] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002875]/20"
            >
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white shadow-xs">
                            {!! $icon($item['icon']) !!}
                        </div>
                        <div class="min-w-0">
                            <h2 class="mb-0 text-sm font-bold text-[#0F172A] truncate group-hover:text-[#002875]">
                                {{ $item['label'] }}
                            </h2>
                            <span class="text-[10px] font-medium uppercase tracking-wider text-[#475569]">
                                Admin Module
                            </span>
                        </div>
                    </div>

                    <p class="mt-3.5 text-xs font-normal text-[#475569] line-clamp-2">
                        {{ $item['desc'] }}
                    </p>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-[#E5E7EB] pt-3">
                    <span class="text-xs font-semibold text-[#002875]">
                        Buka Konfigurasi
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
