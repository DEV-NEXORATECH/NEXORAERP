@props([
    'paginator'    => null,
    'emptyMessage' => 'Tidak ada data yang ditemukan.',
])

<div class="rounded-md border border-[#D1D5DB] bg-white shadow-2xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-0 text-left text-xs text-[#111827]">
            {{ $slot }}
        </table>
    </div>

    @if($paginator && ($paginator->hasPages() || $paginator->total() > 0))
        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between border-t border-[#E5E7EB] bg-[#F9FAFB] px-4 py-3 text-xs text-[#475569]">
            <div>
                Menampilkan <span class="font-semibold text-[#111827]">{{ $paginator->firstItem() ?? 0 }}</span> - <span class="font-semibold text-[#111827]">{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-[#111827]">{{ $paginator->total() }}</span> data
            </div>
            <div>
                {{ $paginator->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
