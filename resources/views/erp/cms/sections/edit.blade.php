@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'Edit Section CMS'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Edit Section: {{ $section->label }}</h1>
            <p class="text-xs text-gray-500">Perbarui konfigurasi section API</p>
        </div>
        <a href="{{ route('cms.sections.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('cms.sections.update', $section) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('put')
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Key Unik <span class="text-red-500">*</span></label>
                    <input name="key" value="{{ old('key', $section->key) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Label Admin <span class="text-red-500">*</span></label>
                    <input name="label" value="{{ old('label', $section->label) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Urutan <span class="text-red-500">*</span></label>
                    <input name="sort_order" type="number" value="{{ old('sort_order', $section->sort_order) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Judul</label>
                    <input name="title" value="{{ old('title', $section->title) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Subtitle</label>
                    <input name="subtitle" value="{{ old('subtitle', $section->subtitle) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-700">Konten Teks Utama</label>
                <textarea name="content" rows="4" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">{{ old('content', $section->content) }}</textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-700">Items JSON</label>
                <textarea name="items_json" rows="6" class="w-full px-3 py-2 font-mono text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-gray-50 text-gray-900">{{ old('items_json', json_encode($section->items_json, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Label Tombol</label>
                    <input name="button_label" value="{{ old('button_label', $section->button_label) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">URL Tombol</label>
                    <input name="button_url" value="{{ old('button_url', $section->button_url) }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-700">Ubah Gambar</label>
                    <input name="image" type="file" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md bg-white text-gray-900">
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                    <input type="checkbox" name="is_visible" value="1" class="rounded border-gray-300 text-[#002875] focus:ring-[#002875]" {{ $section->is_visible ? 'checked' : '' }}>
                    Tampilkan Section di Endpoint API
                </label>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('cms.sections.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Perbarui Section</button>
            </div>
        </form>
    </div>
</div>
@endsection