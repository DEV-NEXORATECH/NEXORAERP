@props([
    'title'       => 'Belum Ada Data',
    'message'     => 'Data belum tersedia untuk periode atau filter yang dipilih.',
    'createUrl'   => null,
    'createLabel' => 'Tambah Sekarang',
])

<div class="flex flex-col items-center justify-center p-8 text-center bg-[#F9FAFB] rounded-[8px] border border-dashed border-[#D1D5DB] my-3">
    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#E5E7EB] text-[#6B7280] mb-2.5">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <h3 class="text-sm font-bold text-[#111827] m-0">{{ $title }}</h3>
    <p class="mt-1 text-xs text-[#6B7280] max-w-sm m-0">{{ $message }}</p>

    @if($createUrl)
        <a href="{{ $createUrl }}" class="mt-3.5 inline-flex items-center gap-1.5 rounded-[8px] bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-2xs">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            <span>{{ $createLabel }}</span>
        </a>
    @endif
</div>
