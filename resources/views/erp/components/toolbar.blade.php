@props([
    'action'       => '',
    'search'       => request('search'),
    'placeholder'  => 'Cari data...',
    'createUrl'    => null,
    'createLabel'  => 'Tambah Data',
    'exportUrl'    => null,
    'canCreate'    => true,
])

<div class="mb-4 flex flex-col gap-3 rounded-md border border-[#D1D5DB] bg-white p-3.5 shadow-2xs sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ $action ?: url()->current() }}" class="flex flex-1 flex-wrap items-center gap-2.5">
        {{-- Search Input --}}
        <div class="relative min-w-[220px] flex-1 sm:max-w-xs">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ $placeholder }}" class="h-9 min-h-0 w-full rounded-md border border-[#D1D5DB] bg-[#F9FAFB] pl-9 pr-3 text-xs text-[#111827] outline-none transition placeholder:text-[#9CA3AF] focus:border-[#002875] focus:bg-white focus:ring-1 focus:ring-[#002875]">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#9CA3AF]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
        </div>

        {{-- Custom Filter Dropdowns Slot --}}
        {{ $filters ?? '' }}

        {{-- Submit & Reset Buttons --}}
        <button type="submit" class="inline-flex h-9 items-center justify-center rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-2xs cursor-pointer">
            <span>Filter</span>
        </button>

        @if(request()->hasAny(['search', 'status', 'project_id', 'department_id', 'category_id', 'client_id', 'vendor_id', 'period', 'from_date', 'to_date']))
            <a href="{{ $action ?: url()->current() }}" class="inline-flex h-9 items-center justify-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#475569] hover:bg-[#F3F4F6] transition shadow-2xs">
                <span>Reset</span>
            </a>
        @endif
    </form>

    {{-- Right Actions: Create, Export, etc. --}}
    <div class="flex flex-wrap items-center gap-2">
        {{ $extraActions ?? '' }}

        @if($exportUrl)
            <a href="{{ $exportUrl }}" class="inline-flex h-9 items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#002875] hover:bg-[#F3F4F6] transition shadow-2xs">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                <span>Export</span>
            </a>
        @endif

        @if($createUrl && $canCreate)
            <a href="{{ $createUrl }}" class="inline-flex h-9 items-center gap-1.5 rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-2xs">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                <span>{{ $createLabel }}</span>
            </a>
        @endif
    </div>
</div>
