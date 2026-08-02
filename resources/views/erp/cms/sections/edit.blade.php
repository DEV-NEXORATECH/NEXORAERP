@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'Edit Section CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.sections.index') }}">Back to Section</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('content') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS SECTION</span>
                    <h1>Edit {{ $section->label }}</h1>
                </div>
            </div>
            <p>Edit satu section saja, bukan digabung dengan blog atau setting lain.</p>
        </div>
    </div>
</section>

<section class="cms-form-card">
    <form method="post" action="{{ route('cms.sections.update', $section) }}" enctype="multipart/form-data" class="cms-form-section">
        @csrf
        @method('put')
        <div class="cms-form-grid">
            <input name="key" value="{{ old('key', $section->key) }}" placeholder="Key unik" required>
            <input name="label" value="{{ old('label', $section->label) }}" placeholder="Label admin" required>
            <input name="sort_order" type="number" value="{{ old('sort_order', $section->sort_order) }}" placeholder="Urutan" required>
            <input name="title" value="{{ old('title', $section->title) }}" placeholder="Judul">
            <input name="subtitle" value="{{ old('subtitle', $section->subtitle) }}" placeholder="Subtitle">
        </div>
        <textarea name="content" placeholder="Konten utama">{{ old('content', $section->content) }}</textarea>
        <textarea class="cms-json" name="items_json" rows="12" placeholder='JSON items: {"id": [], "en": []}'>{{ old('items_json', json_encode($section->items ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea>
        <div class="cms-form-grid two">
            <input name="button_label" value="{{ old('button_label', $section->button_label) }}" placeholder="Label tombol">
            <input name="button_url" value="{{ old('button_url', $section->button_url) }}" placeholder="URL tombol">
            <input name="image" type="file">
            <label class="flex items-center gap-2 normal-case tracking-normal">
                <input type="checkbox" name="is_visible" value="1" class="min-h-0 w-auto" @checked(old('is_visible', $section->is_visible))>
                Tampilkan section
            </label>
        </div>
        @if($section->image_path)
            <p class="muted">Image: {{ $section->image_path }}</p>
        @endif
        <button>Simpan Perubahan</button>
    </form>
</section>
@endsection
