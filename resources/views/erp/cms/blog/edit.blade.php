@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'Edit Artikel Blog'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Edit Artikel: {{ $post->title }}</h1>
            <p class="text-xs text-[#475569]">Perbarui konten artikel blog</p>
        </div>
        <a href="{{ route('cms.blog.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('cms.blog.update', $post) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Judul Artikel <span class="text-red-500">*</span></label>
                    <input name="title" value="{{ old('title', $post->title) }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Slug URL</label>
                    <input name="slug" value="{{ old('slug', $post->slug) }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#334155]">Ringkasan Pendek (Excerpt)</label>
                <textarea name="excerpt" rows="2" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#334155]">Konten Artikel Utama <span class="text-red-500">*</span></label>
                <textarea name="body" rows="10" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">{{ old('body', $post->body) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Status Publikasi</label>
                    <select name="status" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                        <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Waktu Terbit</label>
                    <input name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Ubah Cover (Opsional)</label>
                    <input name="cover" type="file" class="w-full px-3 py-1.5 text-xs border border-[#D1D5DB] rounded-md bg-white text-[#0F172A]">
                </div>
            </div>

            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('cms.blog.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Perbarui Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection