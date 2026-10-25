@props([
    'paginator'    => null,
    'emptyMessage' => 'Tidak ada data yang ditemukan.',
])

<div class="space-y-4">
    <div class="workspace-card rounded-lg border border-[#E5E7EB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full border-collapse text-left text-xs text-[#0F172A]">
                {{ $slot }}
            </table>
        </div>
    </div>

    @if($paginator && ($paginator->hasPages() || $paginator->total() > 0))
        <div class="pagination-card mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3">
            <div>
                Menampilkan <span class="font-semibold text-[#0F172A]">{{ $paginator->firstItem() ?? 0 }}</span> - <span class="font-semibold text-[#0F172A]">{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-[#0F172A]">{{ $paginator->total() }}</span> data
            </div>
            <div>
                {{ $paginator->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
