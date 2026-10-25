@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'CMS Sections'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('cms.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke CMS">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">Headless Content</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Section Builder</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Section Content</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola blok komponen konten website publik dan aplikasi lewat payload API terstruktur.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('cms.sections.create') }}" class="btn btn-primary inline-flex h-9 items-center gap-2 px-3.5 text-xs font-semibold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
                <span>Tambah Section</span>
            </a>
            <div class="inline-flex h-9 items-center gap-1.5 rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A]">
                <span class="text-[#475569]">Total:</span>
                <span class="font-bold text-[#002875]">{{ $sections->total() }} Section</span>
            </div>
        </div>
    </div>

    {{-- Section Cards Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($sections as $section)
            <div class="flex min-h-[170px] flex-col justify-between rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs transition hover:border-[#002875] hover:shadow-sm">
                <div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-[#002875] text-white">
                            {!! $icon('content') !!}
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $section->is_visible ? 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]' : 'bg-[#FEF2F2] text-[#DC2626] border border-[#FCA5A5]' }}">
                            {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                    </div>

                    <div class="mt-3.5">
                        <h2 class="text-sm font-bold text-[#0F172A]">{{ $section->label }}</h2>
                        <p class="mt-1 text-xs text-[#475569] line-clamp-2">{{ $section->title ?: 'Tidak ada judul tambahan.' }}</p>
                    </div>

                    <div class="mt-3 flex items-center gap-2 text-[11px] font-mono text-[#475569]">
                        <span class="bg-[#F1F5F9] px-2 py-0.5 rounded-[4px]">{{ $section->key }}</span>
                        <span>Urutan: {{ $section->sort_order }}</span>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-[#E5E7EB] pt-3">
                    <a href="{{ route('cms.sections.edit', $section) }}" class="btn btn-outline h-7 px-3 text-xs font-semibold">
                        Edit
                    </a>
                    <form method="post" action="{{ route('cms.sections.destroy', $section) }}">
                        @csrf @method('delete')
                        <button type="submit" class="inline-flex h-7 items-center rounded-[6px] border border-[#FECACA] bg-[#FEF2F2] px-2.5 text-xs font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-[#E5E7EB] bg-white p-8 text-center text-xs text-[#94A3B8]">
                Belum ada section konten yang dibuat.
            </div>
        @endforelse
    </div>

    @if($sections->hasPages())
    <div class="mt-4">
        {{ $sections->links() }}
    </div>
    @endif
</div>
@endsection
