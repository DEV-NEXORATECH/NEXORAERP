@extends('layouts.erp', ['activePage' => 'cms', 'pageTitle' => 'CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('admin.index') }}">Back to Admin</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('list') !!}</span>
                <div>
                    <span class="module-eyebrow">NEXORA CMS</span>
                    <h1>CMS Konten & Blog</h1>
                </div>
            </div>
            <p>Kelola konten yang akan dipakai aplikasi/frontend melalui API, tanpa membuat halaman publik di ERP.</p>
        </div>
        <div class="module-count">
            <strong>{{ $sections->count() }}</strong>
            <span>Section</span>
        </div>
    </div>
</section>

<section class="section grid two">
    <div class="card">
        <h2>Section CMS</h2>
        <p class="muted">Section berikut dikelola dari CMS dan bisa diambil lewat API.</p>
        <div class="mt-4 grid gap-2">
            @foreach($staticItems as $item)
                <span class="badge">{{ $item }}</span>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2>Tambah Blog</h2>
        <form method="post" action="{{ route('cms.blog.store') }}" enctype="multipart/form-data" class="grid">
            @csrf
            <input name="title" placeholder="Judul blog" required>
            <textarea name="excerpt" placeholder="Ringkasan pendek"></textarea>
            <textarea name="body" placeholder="Isi artikel"></textarea>
            <div class="form-grid">
                <select name="status" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
                <input name="published_at" type="datetime-local">
            </div>
            <input name="cover" type="file">
            <button>Tambah Blog</button>
        </form>
    </div>
</section>

<section class="section grid">
    @foreach($sections as $section)
        <div class="card">
            <div class="topbar">
                <div>
                    <h2>{{ $section->label }}</h2>
                    <p class="muted">Key: {{ $section->key }}</p>
                </div>
                <span class="badge">{{ $section->is_visible ? 'Visible' : 'Hidden' }}</span>
            </div>
            <form method="post" action="{{ route('cms.sections.update', $section) }}" enctype="multipart/form-data" class="grid">
                @csrf
                @method('put')
                <div class="form-grid">
                    <input name="label" value="{{ $section->label }}" placeholder="Label admin" required>
                    <input name="sort_order" type="number" value="{{ $section->sort_order }}" placeholder="Urutan" required>
                    <input name="title" value="{{ $section->title }}" placeholder="Judul">
                    <input name="subtitle" value="{{ $section->subtitle }}" placeholder="Subtitle">
                </div>
                <textarea name="content" placeholder="Konten utama">{{ $section->content }}</textarea>
                <textarea name="items_json" rows="12" placeholder='JSON items: {"id": [], "en": []}'>{{ json_encode($section->items ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</textarea>
                <div class="form-grid">
                    <input name="button_label" value="{{ $section->button_label }}" placeholder="Label tombol">
                    <input name="button_url" value="{{ $section->button_url }}" placeholder="URL tombol">
                </div>
                <div class="form-grid">
                    <input name="image" type="file">
                    <label class="flex items-center gap-2 normal-case tracking-normal">
                        <input type="checkbox" name="is_visible" value="1" class="min-h-0 w-auto" @checked($section->is_visible)>
                        Tampilkan section
                    </label>
                </div>
                @if($section->image_path)
                    <p class="muted">Image: {{ $section->image_path }}</p>
                @endif
                <button>Simpan Section</button>
            </form>
        </div>
    @endforeach
</section>

<section class="section card">
    <h2>Daftar Blog</h2>
    <div class="grid">
        @forelse($posts as $post)
            <details>
                <summary>{{ $post->title }} - {{ strtoupper($post->status) }}</summary>
                <form method="post" action="{{ route('cms.blog.update', $post) }}" enctype="multipart/form-data" class="mt-4 grid">
                    @csrf
                    @method('put')
                    <div class="form-grid">
                        <input name="title" value="{{ $post->title }}" required>
                        <input name="slug" value="{{ $post->slug }}" placeholder="slug">
                    </div>
                    <textarea name="excerpt">{{ $post->excerpt }}</textarea>
                    <textarea name="body">{{ $post->body }}</textarea>
                    <div class="form-grid">
                        <select name="status" required>
                            <option value="draft" @selected($post->status === 'draft')>Draft</option>
                            <option value="published" @selected($post->status === 'published')>Published</option>
                        </select>
                        <input name="published_at" type="datetime-local" value="{{ $post->published_at?->format('Y-m-d\TH:i') }}">
                    </div>
                    <input name="cover" type="file">
                    <div class="actions">
                        <button>Update Blog</button>
                    </div>
                </form>
                <form method="post" action="{{ route('cms.blog.destroy', $post) }}" class="mt-3">
                    @csrf
                    @method('delete')
                    <button class="danger" onclick="return confirm('Hapus blog ini?')">Hapus Blog</button>
                </form>
            </details>
        @empty
            <p class="muted">Belum ada blog.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</section>
@endsection
