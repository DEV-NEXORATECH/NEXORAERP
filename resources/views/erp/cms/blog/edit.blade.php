@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'Edit Artikel Blog'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Edit Artikel: {{ $post->title }}</h1>
            <p class="text-xs text-gray-500">Perbarui konten artikel blog</p>
        </div>
        <a href="{{ route('cms.blog.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('cms.blog.update', $post) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Judul Artikel <span class="text-red-500">*</span></label>
                    <input name="title" value="{{ old('title', $post->title) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Slug URL</label>
                    <input name="slug" value="{{ old('slug', $post->slug) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-700">Ringkasan Pendek (Excerpt)</label>
                <textarea name="excerpt" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-700">Konten Artikel Utama <span class="text-red-500">*</span></label>
                <textarea name="body" rows="10" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">{{ old('body', $post->body) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Status Publikasi</label>
                    <select name="status" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                        <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Waktu Terbit</label>
                    <input name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Ubah Cover (Opsional)</label>
                    <input name="cover" type="file" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md bg-white text-gray-900">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('cms.blog.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Perbarui Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection