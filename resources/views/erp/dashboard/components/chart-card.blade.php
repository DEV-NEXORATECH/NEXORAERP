@props([
    'title'       => '',
    'subtitle'    => '',
    'badge'       => null,
    'badgeRoute'  => null,
    'chartId'     => '',
    'class'       => '',
])

<div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs flex flex-col justify-between {{ $class }}">
    {{-- Header --}}
    <div class="mb-4 flex items-start justify-between gap-3 border-b border-[#F1F5F9] pb-3">
        <div>
            <h2 class="text-sm sm:text-base font-bold tracking-tight text-[#0F172A] m-0">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-0.5 text-xs text-[#64748B]">{{ $subtitle }}</p>
            @endif
        </div>
        @if($badge)
            @if($badgeRoute)
                <a href="{{ $badgeRoute }}" class="inline-flex items-center gap-1 rounded-[6px] border border-[#E5E7EB] bg-[#F8FAFC] px-2.5 py-1 text-[11px] font-semibold text-[#002875] hover:bg-[#F1F5F9] transition">
                    <span>{{ $badge }}</span>
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @else
                <span class="inline-flex rounded-[6px] border border-[#E5E7EB] bg-[#F8FAFC] px-2.5 py-0.5 text-[11px] font-semibold text-[#475569]">
                    {{ $badge }}
                </span>
            @endif
        @endif
    </div>

    {{-- Chart Body --}}
    <div class="relative w-full flex-1">
        <div id="{{ $chartId }}" class="w-full"></div>
    </div>

    {{-- Footer slot if any --}}
    @if(isset($slot))
        {{ $slot }}
    @endif
</div>
