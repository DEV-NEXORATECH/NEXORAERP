@props([
    'label'       => '',
    'value'       => '',
    'icon'        => 'dashboard',
    'color'       => '#0B2345',
    'trend'       => null,
    'sub'         => null,
    'route'       => null,
    'sparkline'   => null,
    'chartId'     => null,
    'format'      => 'text', // 'currency', 'number', 'text'
])

@php
    $displayValue = $value;
    if ($format === 'currency' && is_numeric($value)) {
        $displayValue = 'Rp ' . number_format((float) $value, 0, ',', '.');
    } elseif ($format === 'number' && is_numeric($value)) {
        $displayValue = number_format((float) $value, 0, ',', '.');
    }
@endphp

<div class="group relative flex flex-col justify-between rounded-[12px] border border-[#D9E2EC] bg-white p-4 shadow-xs transition hover:border-[#0B2345]/30 hover:shadow-sm">
    {{-- Top: Icon & Trend badge --}}
    @php
        $renderIcon = app('view')->shared('icon');
        $iconName = (isset($icon) && is_string($icon)) ? $icon : ($kpi['icon'] ?? 'dashboard');
    @endphp
    <div class="flex items-center justify-between gap-2">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[8px]" style="background-color: {{ $color }}15; color: {{ $color }};">
            <div class="h-5 w-5 flex items-center justify-center">
                {!! is_callable($renderIcon) ? $renderIcon($iconName) : '' !!}
            </div>
        </div>

        @if($trend && isset($trend['label']))
            @php
                $isUp = ($trend['direction'] ?? '') === 'up';
                $isDown = ($trend['direction'] ?? '') === 'down';
                $isGood = $trend['is_positive'] ?? true;
                $badgeBg = $isGood ? 'bg-[#16A34A]/10 text-[#16A34A] border-[#16A34A]/20' : 'bg-[#DC2626]/10 text-[#DC2626] border-[#DC2626]/20';
                if (($trend['direction'] ?? '') === 'neutral') {
                    $badgeBg = 'bg-[#F1F5F9] text-[#475569] border-[#E2E8F0]';
                }
            @endphp
            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] font-semibold {{ $badgeBg }}">
                @if($isUp)
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m18 15-6-6-6 6"/></svg>
                @elseif($isDown)
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                @endif
                <span>{{ $trend['label'] }}</span>
            </span>
        @elseif($sub)
            <span class="text-[11px] font-medium text-[#64748B]">{{ $sub }}</span>
        @endif
    </div>

    {{-- Middle: Value & Label --}}
    <div class="mt-3">
        <div class="text-2xl sm:text-[28px] font-bold tracking-tight text-[#0F172A] leading-tight">
            {{ $displayValue }}
        </div>
        <div class="mt-1 text-xs font-semibold text-[#475569]">
            {{ $label }}
        </div>
    </div>

    {{-- Bottom: Mini sparkline or drilldown action --}}
    <div class="mt-3 flex items-center justify-between border-t border-[#F1F5F9] pt-2 text-[11px]">
        @if($route)
            <a href="{{ $route }}" class="inline-flex items-center gap-1 font-semibold text-[#0B2345] hover:text-[#2563EB] transition">
                <span>Detail</span>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <span class="text-[#94A3B8] font-medium">Realtime KPI</span>
        @endif

        @if($chartId && $sparkline && count($sparkline) > 0)
            <div id="{{ $chartId }}" class="h-7 w-20"></div>
        @endif
    </div>
</div>
