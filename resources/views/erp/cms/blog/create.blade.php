@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'Tambah Blog CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.blog.index') }}">Back to Blog</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('blog') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS BLOG</span>
                    <h1>Tambah Blog</h1>
                </div>
            </div>
            <p>Buat artikel baru untuk API CMS.</p>
        </div>
    </div>
</section>

<section class="cms-form-card">
    <form method="post" action="{{ route('cms.blog.store') }}" enctype="multipart/form-data" class="cms-form-section">
        @csrf
        <input name="title" value="{{ old('title') }}" placeholder="Judul blog" required>
        <input name="slug" value="{{ old('slug') }}" placeholder="Slug opsional">
        <textarea name="excerpt" placeholder="Ringkasan pendek">{{ old('excerpt') }}</textarea>
        <textarea name="body" rows="12" placeholder="Isi artikel">{{ old('body') }}</textarea>
        <div class="cms-form-grid">
            <select name="status" required>
                <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                <option value="published" @selected(old('status') === 'published')>Published</option>
            </select>
            <input name="published_at" type="datetime-local" value="{{ old('published_at') }}">
            <input name="cover" type="file">
        </div>
        <button>Simpan Blog</button>
    </form>
</section>
@endsection
