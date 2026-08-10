@extends('layouts.erp', ['activePage' => 'cms-blog', 'pageTitle' => 'CMS Blog'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.index') }}">Back to CMS</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('blog') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS</span>
                    <h1>Blog / Article</h1>
                </div>
            </div>
            <p>Kelola artikel yang keluar lewat API blog CMS.</p>
        </div>
        <div class="module-count">
            <strong>{{ $posts->total() }}</strong>
            <span>Post</span>
        </div>
    </div>
</section>

<section class="cms-panel">
    <div class="cms-panel-head">
        <div>
            <h2>Daftar Blog</h2>
            <p class="muted">Setiap post punya halaman create/edit sendiri.</p>
        </div>
        <a class="button" href="{{ route('cms.blog.create') }}">Tambah Blog</a>
    </div>

    <div class="cms-list">
        @forelse($posts as $post)
            <div class="cms-row">
                <div class="cms-row-main">
                    <div class="cms-row-title">
                        <h3>{{ $post->title }}</h3>
                        <span class="badge {{ $post->status === 'published' ? 'badge-active' : 'badge-draft' }}">{{ strtoupper($post->status) }}</span>
                    </div>
                    <div class="cms-row-meta">
                        <span>Slug: {{ $post->slug }}</span>
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y H:i') : 'Belum publish' }}</span>
                    </div>
                    <p>{{ $post->excerpt ?: 'Belum ada ringkasan.' }}</p>
                </div>
                <div class="cms-row-actions">
                    <a class="button ghost" href="{{ route('cms.blog.edit', $post) }}">Edit</a>
                    <form method="post" action="{{ route('cms.blog.destroy', $post) }}">
                        @csrf
                        @method('delete')
                        <button class="danger">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted p-6">Belum ada blog.</p>
        @endforelse
    </div>

    <div class="p-5">{{ $posts->links() }}</div>
</section>
@endsection
