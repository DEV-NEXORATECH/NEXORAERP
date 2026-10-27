@props([
    'action'       => '',
    'search'       => request('search'),
    'placeholder'  => 'Cari data...',
    'createUrl'    => null,
    'createLabel'  => 'Tambah Data',
    'exportUrl'    => null,
    'canCreate'    => true,
])

<div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
    <form method="GET" action="{{ $action ?: url()->current() }}" class="filter-toolbar erp-filter-toolbar">
        {{-- Search Input --}}
        <input type="text" name="search" value="{{ $search }}" placeholder="{{ $placeholder }}" class="filter-search">

        {{-- Custom Filter Dropdowns Slot --}}
        {{ $filters ?? '' }}

        {{-- Submit & Reset Buttons --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="submit" class="inline-flex h-8 items-center justify-center rounded-[6px] bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition shadow-xs cursor-pointer">
                <span>Filter</span>
            </button>

            @if(request()->hasAny(['search', 'status', 'project_id', 'department_id', 'category_id', 'client_id', 'vendor_id', 'period', 'from_date', 'to_date', 'date_from', 'date_to', 'mode', 'type', 'employee_id']))
                <a href="{{ $action ?: url()->current() }}" class="inline-flex h-8 items-center justify-center rounded-[6px] border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-[#475569] hover:bg-[#F3F4F6] transition shadow-xs">
                    <span>Reset</span>
                </a>
            @endif
        </div>
    </form>
</div>
