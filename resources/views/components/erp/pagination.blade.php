@props([
    'paginator' => null,
])

@if($paginator && ($paginator->hasPages() || $paginator->total() > 0))
    <div class="pagination-card mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
        <div class="font-normal text-[#475569]">
            Menampilkan <span class="font-semibold text-[#0F172A]">{{ $paginator->firstItem() ?? ($paginator->total() > 0 ? 1 : 0) }}</span> - <span class="font-semibold text-[#0F172A]">{{ $paginator->lastItem() ?? $paginator->total() }}</span> dari <span class="font-semibold text-[#0F172A]">{{ $paginator->total() }}</span> data
        </div>
        @if($paginator->hasPages())
            <div>
                {{ $paginator->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endif
