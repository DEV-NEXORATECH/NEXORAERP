@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'CMS Blog'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('cms.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-[8px] border border-[#D9E2EC] bg-white text-[#475569] transition hover:border-[#0B2345] hover:text-[#0B2345]" title="Kembali ke CMS">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Headless Content</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Editorial Engine</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Blog & Artikel</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Publikasi artikel, wawasan industri, dan pengumuman untuk konsumsi endpoint blog API.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('cms.blog.create') }}" class="btn btn-primary inline-flex h-9 items-center gap-2 px-3.5 text-xs font-semibold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
                <span>Tulis Artikel Baru</span>
            </a>
            <div class="inline-flex h-9 items-center gap-1.5 rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A]">
                <span class="text-[#475569]">Total Post:</span>
                <span class="font-bold text-[#0B2345]">{{ $posts->total() }}</span>
            </div>
        </div>
    </div>

    {{-- Blog Articles Table / List --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D9E2EC] text-[#475569] font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Judul & Ringkasan</th>
                        <th class="px-4 py-3.5">Slug URL</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Tanggal Terbit</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D9E2EC]">
                    @forelse($posts as $post)
                    <tr class="hover:bg-[#F8FAFC]/60 transition">
                        <td class="px-5 py-3.5 max-w-xs">
                            <div class="font-bold text-[#0F172A] line-clamp-1">{{ $post->title }}</div>
                            <div class="text-[11px] text-[#475569] line-clamp-1">{{ $post->excerpt ?: 'Tidak ada ringkasan.' }}</div>
                        </td>
                        <td class="px-4 py-3.5 font-mono text-[11px] text-[#0B2345]">
                            {{ $post->slug }}
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $post->status === 'published' ? 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]' : 'bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-[#475569]">
                            {{ $post->published_at ? $post->published_at->format('d M Y H:i') : 'Draft' }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1.5 justify-end">
                                <a href="{{ route('cms.blog.edit', $post) }}" class="btn btn-outline h-7 px-3 text-[11px] font-medium">
                                    Edit
                                </a>
                                <form method="post" action="{{ route('cms.blog.destroy', $post) }}">
                                    @csrf @method('delete')
                                    <button type="submit" class="inline-flex h-7 items-center rounded-[6px] border border-[#FECACA] bg-[#FEF2F2] px-2.5 text-[11px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-xs text-[#94A3B8]">
                            Belum ada artikel yang diterbitkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="flex items-center justify-between border-t border-[#D9E2EC] px-5 py-3 bg-[#F8FAFC]">
            <span class="text-xs text-[#475569]">Menampilkan {{ $posts->firstItem() }}–{{ $posts->lastItem() }} dari {{ $posts->total() }}</span>
            <div>{{ $posts->links() }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
