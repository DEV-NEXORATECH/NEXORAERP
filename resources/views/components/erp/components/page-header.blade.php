@props([
    'title'      => '',
    'subtitle'   => '',
    'breadcrumb' => '',
    'icon'       => 'list',
])

<div class="mb-4 flex flex-col gap-3 rounded-[10px] border border-[#D1D5DB] bg-white p-4 shadow-2xs sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
        @if($icon)
            @php
                $renderIcon = app('view')->shared('icon');
                $iconName = is_string($icon) ? $icon : 'list';
            @endphp
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[8px] bg-[#002875] text-white shadow-2xs">
                <div class="h-5 w-5 flex items-center justify-center">
                    {!! is_callable($renderIcon) ? $renderIcon($iconName) : '' !!}
                </div>
            </div>
        @endif
        <div>
            @if($breadcrumb)
                <nav class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wider text-[#475569]">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#002875] transition">ERP NEXORA</a>
                    <span>·</span>
                    <span class="text-[#002875]">{{ $breadcrumb }}</span>
                </nav>
            @endif
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827] m-0">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-0.5 text-xs text-[#475569] m-0">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if(isset($actions) || isset($slot))
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions ?? $slot }}
        </div>
    @endif
</div>
