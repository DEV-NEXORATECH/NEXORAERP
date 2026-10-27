@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'CMS Blog'])

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
                    <span class="text-[11px] font-medium text-[#475569]">Editorial Engine</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Blog & Artikel</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Publikasi artikel, wawasan industri, dan pengumuman untuk konsumsi endpoint blog API.</p>
            </div>
        </div>

        <x-erp.toolbar-actions module="cms_posts" :create-route="route('cms.blog.create')" create-label="+ Tulis Artikel Baru" />
    </div>

    {{-- Blog Articles Table / List --}}
    <div class="rounded-lg border border-[#E5E7EB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB] text-[#475569] font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Judul & Ringkasan</th>
                        <th class="px-4 py-3.5">Slug URL</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Tanggal Terbit</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($posts as $post)
                    <tr class="hover:bg-[#F8FAFC]/60 transition">
                        <td class="px-5 py-3.5 max-w-xs">
                            <div class="font-bold text-[#0F172A] line-clamp-1">{{ $post->title }}</div>
                            <div class="text-[11px] text-[#475569] line-clamp-1">{{ $post->excerpt ?: 'Tidak ada ringkasan.' }}</div>
                        </td>
                        <td class="px-4 py-3.5 font-mono text-[11px] text-[#002875]">
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
    </div>

    <x-erp.pagination :paginator="$posts" />
</div>

<x-erp.import-modal module="blog" title="Impor Data Blog" />
@endsection