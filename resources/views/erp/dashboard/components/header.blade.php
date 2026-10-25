@props([
    'title'       => 'Dashboard',
    'subtitle'    => 'Ringkasan operasional dan analitik enterprise.',
    'icon'        => 'dashboard',
    'breadcrumb'  => 'Dashboard',
    'period'      => $data['range']['period'] ?? 'this_month',
    'periodLabel' => $data['range']['label'] ?? 'Bulan Ini',
    'exportable'  => true,
])

<div class="mb-5 rounded-[12px] border border-[#D9E2EC] bg-white p-4 sm:p-5 shadow-xs">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        {{-- Left: Breadcrumb, Icon, Title & Subtitle --}}
        @php
            $renderIcon = app('view')->shared('icon');
            $iconName = (isset($icon) && is_string($icon)) ? $icon : 'dashboard';
        @endphp
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                <div class="h-6 w-6 flex items-center justify-center">
                    {!! is_callable($renderIcon) ? $renderIcon($iconName) : '' !!}
                </div>
            </div>
            <div>
                <nav class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-[#475569]">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#0B2345] transition">ERP NEXORA <span class="sr-only">NEXORA ERP</span></a>
                    <span>·</span>
                    <span class="text-[#0B2345]">{{ $breadcrumb }}</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">{{ $title }}</h1>
                <p class="mt-0.5 text-xs text-[#475569]">{{ $subtitle }}</p>
            </div>
        </div>

        {{-- Right: Date Range Selector, Filters & Actions --}}
        <div class="flex flex-wrap items-center gap-2 self-start lg:self-auto">
            <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-center gap-2">
                {{-- Date Range Dropdown --}}
                <div class="relative">
                    <select name="period" data-dashboard-period class="h-9 min-h-0 rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3 py-1.5 text-xs font-semibold text-[#0F172A] outline-none transition hover:border-[#0B2345] focus:border-[#0B2345] focus:bg-white">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="7_days" {{ $period === '7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30_days" {{ $period === '30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="this_quarter" {{ $period === 'this_quarter' ? 'selected' : '' }}>Kuartal Ini</option>
                        <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Pilih Rentang Tanggal</option>
                    </select>
                </div>

                {{-- Custom Date Range Inputs --}}
                <div data-custom-dates class="flex items-center gap-1.5 {{ $period === 'custom' ? '' : 'hidden' }}">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-9 min-h-0 w-32 rounded-[8px] border border-[#D9E2EC] bg-white px-2 py-1 text-xs text-[#0F172A]" placeholder="Mulai">
                    <span class="text-xs text-[#475569]">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-9 min-h-0 w-32 rounded-[8px] border border-[#D9E2EC] bg-white px-2 py-1 text-xs text-[#0F172A]" placeholder="Selesai">
                    <button type="submit" class="h-9 rounded-[8px] bg-[#0B2345] px-3 text-xs font-semibold text-white hover:bg-[#123A6B] transition">Terapkan</button>
                </div>

                {{-- Refresh Button --}}
                <a href="{{ url()->current() }}" class="flex h-9 items-center justify-center rounded-[8px] border border-[#D9E2EC] bg-white px-2.5 text-xs font-medium text-[#475569] hover:bg-[#F4F7FB] hover:text-[#0B2345] transition" title="Refresh Data">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                </a>

                {{-- Export Button --}}
                @if($exportable)
                    <button type="button" onclick="window.print()" class="flex h-9 items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-white px-3 text-xs font-medium text-[#475569] hover:bg-[#F4F7FB] hover:text-[#0B2345] transition" title="Cetak / Unduh PDF">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                @endif
            </form>

            {{-- Last Updated Indicator --}}
            <div class="hidden sm:flex items-center gap-1.5 rounded-[8px] border border-[#D9E2EC]/60 bg-[#F8FAFC] px-2.5 py-1.5 text-[11px] font-medium text-[#64748B]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#16A34A]"></span>
                <span>Live Data</span>
            </div>
        </div>
    </div>
</div>
