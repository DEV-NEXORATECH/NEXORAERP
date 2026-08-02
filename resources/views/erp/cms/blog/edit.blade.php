@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'Edit Blog CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.blog.index') }}">Back to Blog</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('blog') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS BLOG</span>
                    <h1>Edit Blog</h1>
                </div>
            </div>
            <p>Edit satu artikel saja, bukan digabung dengan section.</p>
        </div>
    </div>
</section>

<section class="cms-form-card">
    <form method="post" action="{{ route('cms.blog.update', $post) }}" enctype="multipart/form-data" class="cms-form-section">
        @csrf
        @method('put')
        <input name="title" value="{{ old('title', $post->title) }}" placeholder="Judul blog" required>
        <input name="slug" value="{{ old('slug', $post->slug) }}" placeholder="Slug">
        <textarea name="excerpt" placeholder="Ringkasan pendek">{{ old('excerpt', $post->excerpt) }}</textarea>
        <textarea name="body" rows="12" placeholder="Isi artikel">{{ old('body', $post->body) }}</textarea>
        <div class="cms-form-grid">
            <select name="status" required>
                <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
            </select>
            <input name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
            <input name="cover" type="file">
        </div>
        @if($post->cover_path)
            <p class="muted">Cover: {{ $post->cover_path }}</p>
        @endif
        <button>Simpan Perubahan</button>
    </form>
</section>
@endsection
