@extends('layouts.erp', ['activePage' => request()->routeIs('modules.index') ? 'modules' : 'all-menu', 'pageTitle' => 'ERP Modules'])

@section('content')
@php
    $hubOrder = ['Main', 'Reports', 'Finance', 'HR', 'Sales', 'Procurement', 'Inventory'];
    $hubNames = [
        'Main'        => 'Dashboard Hub',
        'Reports'     => 'Report Hub',
        'Finance'     => 'Finance Hub',
        'HR'          => 'HR Hub',
        'Sales'       => 'Sales Hub',
        'Procurement' => 'Procurement Hub',
        'Inventory'   => 'Inventory Hub',
    ];
    $hubDescriptions = [
        'Main'        => 'Pusat ringkasan operasional bisnis, KPI real-time, dan status approval perusahaan.',
        'Reports'     => 'Analisis laporan komprehensif mulai dari profit loss, balance sheet, cashflow, hingga audit ledger.',
        'Finance'     => 'Pengelolaan arus kas, invoice pelanggan, tagihan vendor, jurnal akuntansi, dan rekonsiliasi bank.',
        'HR'          => 'Manajemen data karyawan, rekap kehadiran, timesheet project, payroll gaji, dan evaluasi kinerja.',
        'Sales'       => 'Pipeline prospek leads, inquiry klien, proposal penawaran harga, kontrak, hingga sales order.',
        'Procurement' => 'Alur pengadaan barang, verifikasi penerimaan barang (goods receipt), dan kontrak supplier.',
        'Inventory'   => 'Manajemen master produk, kontrol multi-gudang, perpindahan stok, dan penyesuaian inventory.',
    ];
    $hubIcons = [
        'Main'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
        'Reports'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M3 3v18h18"/><path d="M7 16v-5M12 16V7M17 16v-8"/></svg>',
        'Finance'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="m3 17 6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
        'HR'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'Sales'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M8 13h8M8 17h5"/></svg>',
        'Procurement' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'Inventory'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>',
    ];

    $orderedMenus = collect($hubOrder)
        ->filter(fn ($section) => isset($menus[$section]))
        ->mapWithKeys(fn ($section) => [$section => $menus[$section]]);
@endphp

<div class="space-y-5" id="module-hub-container">
    {{-- ── 3. Page Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                {!! $icon('dashboard') !!}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Application Hub</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Core Navigation</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">ERP Modules</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Akses dan kelola seluruh modul bisnis dalam satu tempat.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Search Bar --}}
            <div class="relative w-full sm:w-64">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#475569]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                </span>
                <input
                    type="text"
                    id="module-search-input"
                    placeholder="Cari modul..."
                    class="h-9 w-full rounded-[8px] border border-[#D9E2EC] bg-white pl-9 pr-3 text-xs text-[#0F172A] placeholder:text-[#475569]/50 transition outline-none focus:border-[#0B2345] focus:ring-1 focus:ring-[#0B2345]/15"
                >
            </div>

            {{-- Module Counter Badge --}}
            <div class="inline-flex h-9 items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3 text-xs font-semibold text-[#0F172A]">
                <span class="text-[#475569]">Total:</span>
                <span class="font-bold text-[#0B2345]" id="module-count-badge">{{ $orderedMenus->count() }} Modul</span>
            </div>
        </div>
    </div>

    {{-- ── 4. Module Cards Grid ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="module-cards-grid">
        @foreach($orderedMenus as $section => $items)
            @php
                $hubTitle = $hubNames[$section] ?? $section;
                $hubDesc  = $hubDescriptions[$section] ?? 'Akses menu dan fitur kerja operasional untuk '.$hubTitle.'.';
                $menuCount = count($items);
                $routeUrl = route('modules.show', str($section)->slug());
            @endphp
            <a
                href="{{ $routeUrl }}"
                class="module-card group flex min-h-[200px] flex-col justify-between rounded-[12px] border border-[#D9E2EC] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#0B2345] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#0B2345]/20"
                data-module-title="{{ strtolower($hubTitle) }}"
                data-module-desc="{{ strtolower($hubDesc) }}"
            >
                <div>
                    {{-- Top Section: Icon, Title, Menu Count Badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                                {!! $hubIcons[$section] ?? $hubIcons['Main'] !!}
                            </div>
                            <div class="min-w-0">
                                <h2 class="mb-0 text-base font-bold leading-tight text-[#0F172A] truncate group-hover:text-[#0B2345]">
                                    {{ $hubTitle }}
                                </h2>
                                <span class="text-[11px] font-medium text-[#475569]">
                                    {{ strtoupper($section) }}
                                </span>
                            </div>
                        </div>

                        {{-- Menu Badge (Navy Solid) --}}
                        <span class="inline-flex shrink-0 items-center rounded-full bg-[#0B2345] px-2.5 py-0.5 text-[11px] font-bold text-white shadow-xs">
                            {{ $menuCount }} Menu
                        </span>
                    </div>

                    {{-- Middle Section: Functional Description --}}
                    <p class="mt-3 text-[13px] font-normal leading-relaxed text-[#475569] line-clamp-2">
                        {{ $hubDesc }}
                    </p>
                </div>

                {{-- Bottom Section: Divider, "Lihat Modul", Arrow Right --}}
                <div class="mt-4 flex items-center justify-between border-t border-[#D9E2EC] pt-3">
                    <span class="text-xs font-semibold text-[#0B2345]">
                        Lihat Modul
                    </span>
                    <span class="text-[#0B2345] transition-transform duration-200 group-hover:translate-x-1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Empty State (Search Not Found) --}}
    <div id="module-empty-state" class="hidden flex-col items-center justify-center rounded-[12px] border border-dashed border-[#D9E2EC] bg-white p-12 text-center shadow-xs">
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-[10px] bg-[#0B2345]/5 text-[#0B2345]">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-[#0F172A]">Modul Tidak Ditemukan</h3>
        <p class="mt-1 text-xs text-[#475569]">Tidak ada modul yang cocok dengan kata kunci pencarian Anda.</p>
        <button
            type="button"
            id="btn-reset-search"
            class="mt-4 inline-flex items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-white px-3 py-1.5 text-xs font-semibold text-[#0B2345] hover:bg-[#F4F7FB]"
        >
            Reset Pencarian
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('module-search-input');
        const cardsGrid   = document.getElementById('module-cards-grid');
        const emptyState  = document.getElementById('module-empty-state');
        const resetBtn    = document.getElementById('btn-reset-search');
        const countBadge  = document.getElementById('module-count-badge');

        if (!searchInput || !cardsGrid) return;

        const cards = Array.from(cardsGrid.querySelectorAll('.module-card'));
        const totalCards = cards.length;

        const handleSearch = () => {
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.getAttribute('data-module-title') || '';
                const desc  = card.getAttribute('data-module-desc') || '';

                if (title.includes(query) || desc.includes(query)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (countBadge) {
                countBadge.textContent = `${visibleCount} Modul`;
            }

            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
        };

        searchInput.addEventListener('input', handleSearch);

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                handleSearch();
                searchInput.focus();
            });
        }
    });
</script>
@endsection

