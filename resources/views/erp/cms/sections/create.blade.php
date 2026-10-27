@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'Tambah Section CMS'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Tambah Section Konten</h1>
            <p class="text-xs text-[#475569]">Buat blok section baru untuk API CMS</p>
        </div>
        <a href="{{ route('cms.sections.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition shadow-sm">
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-[#D1D5DB] p-6 shadow-sm">
        <form method="post" action="{{ route('cms.sections.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Key Unik <span class="text-red-500">*</span></label>
                    <input name="key" value="{{ old('key') }}" placeholder="contoh: hero-banner" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Label Admin <span class="text-red-500">*</span></label>
                    <input name="label" value="{{ old('label') }}" placeholder="Hero Section" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Urutan (Sort Order) <span class="text-red-500">*</span></label>
                    <input name="sort_order" type="number" value="{{ old('sort_order', 10) }}" required class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Judul (Title)</label>
                    <input name="title" value="{{ old('title') }}" placeholder="Judul section" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Subtitle</label>
                    <input name="subtitle" value="{{ old('subtitle') }}" placeholder="Sub judul keterangan" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#334155]">Konten Teks Utama</label>
                <textarea name="content" rows="4" placeholder="Deskripsi atau teks paragraf section..." class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">{{ old('content') }}</textarea>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#334155]">Items JSON (Struktur Array Dinamis)</label>
                <textarea name="items_json" rows="6" font-mono placeholder='{"id": [], "en": []}' class="w-full px-3 py-2 font-mono text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-[#F8FAFC] text-[#0F172A]">{{ old('items_json', '{}') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Label Tombol (CTA)</label>
                    <input name="button_label" value="{{ old('button_label') }}" placeholder="Hubungi Kami" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">URL Tombol</label>
                    <input name="button_url" value="{{ old('button_url') }}" placeholder="/contact" class="w-full px-3 py-2 text-xs border border-[#D1D5DB] rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-[#0F172A]">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#334155]">Upload Gambar</label>
                    <input name="image" type="file" class="w-full px-3 py-1.5 text-xs border border-[#D1D5DB] rounded-md bg-white text-[#0F172A]">
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-[#334155] cursor-pointer">
                    <input type="checkbox" name="is_visible" value="1" class="rounded border-[#D1D5DB] text-[#002875] focus:ring-[#002875]" checked>
                    Tampilkan Section di Endpoint API
                </label>
            </div>

            <div class="pt-4 border-t border-[#D1D5DB] flex items-center justify-end gap-2">
                <a href="{{ route('cms.sections.index') }}" class="px-3.5 py-2 text-xs font-medium text-[#475569] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F8FAFC] transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Simpan Section</button>
            </div>
        </form>
    </div>
</div>
@endsection