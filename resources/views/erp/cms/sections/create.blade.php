@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'Tambah Section CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.sections.index') }}">Back to Section</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('content') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS SECTION</span>
                    <h1>Tambah Section</h1>
                </div>
            </div>
            <p>Buat section baru untuk dipakai oleh API CMS.</p>
        </div>
    </div>
</section>

<section class="cms-form-card">
    <form method="post" action="{{ route('cms.sections.store') }}" enctype="multipart/form-data" class="cms-form-section">
        @csrf
        <div class="cms-form-grid">
            <input name="key" value="{{ old('key') }}" placeholder="Key unik, contoh: hero" required>
            <input name="label" value="{{ old('label') }}" placeholder="Label admin" required>
            <input name="sort_order" type="number" value="{{ old('sort_order', 10) }}" placeholder="Urutan" required>
            <input name="title" value="{{ old('title') }}" placeholder="Judul">
            <input name="subtitle" value="{{ old('subtitle') }}" placeholder="Subtitle">
        </div>
        <textarea name="content" placeholder="Konten utama">{{ old('content') }}</textarea>
        <textarea class="cms-json" name="items_json" rows="12" placeholder='JSON items: {"id": [], "en": []}'>{{ old('items_json', '{}') }}</textarea>
        <div class="cms-form-grid two">
            <input name="button_label" value="{{ old('button_label') }}" placeholder="Label tombol">
            <input name="button_url" value="{{ old('button_url') }}" placeholder="URL tombol">
            <input name="image" type="file">
            <label class="flex items-center gap-2 normal-case tracking-normal">
                <input type="checkbox" name="is_visible" value="1" class="min-h-0 w-auto" checked>
                Tampilkan section
            </label>
        </div>
        <button>Simpan Section</button>
    </form>
</section>
@endsection
