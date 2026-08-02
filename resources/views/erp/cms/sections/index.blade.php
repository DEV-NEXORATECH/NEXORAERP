@extends('layouts.erp', ['activePage' => 'cms-sections', 'pageTitle' => 'CMS Sections'])

@section('content')
<section class="module-detail-page cms-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.index') }}">Back to CMS</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('content') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS</span>
                    <h1>Section Content</h1>
                </div>
            </div>
            <p>Kelola section konten yang keluar lewat API CMS.</p>
        </div>
        <div class="module-count">
            <strong>{{ $sections->total() }}</strong>
            <span>Section</span>
        </div>
    </div>
</section>

<section class="cms-panel">
    <div class="cms-panel-head">
        <div>
            <h2>Daftar Section</h2>
            <p class="muted">Setiap section punya halaman edit sendiri.</p>
        </div>
        <a class="button" href="{{ route('cms.sections.create') }}">Tambah Section</a>
    </div>

    <div class="cms-card-grid">
        @forelse($sections as $section)
            <div class="cms-section-card">
                <div class="cms-section-card-top">
                    <span class="cms-section-icon">{!! $icon('content') !!}</span>
                    <span class="badge {{ $section->is_visible ? 'badge-active' : 'badge-void' }}">{{ $section->is_visible ? 'Visible' : 'Hidden' }}</span>
                </div>
                <div class="cms-section-card-body">
                    <h3>{{ $section->label }}</h3>
                    <p>{{ $section->title ?: 'Belum ada title.' }}</p>
                </div>
                <div class="cms-row-meta">
                    <span>{{ $section->key }}</span>
                    <span>Order {{ $section->sort_order }}</span>
                </div>
                <div class="cms-section-card-actions">
                    <a class="button ghost" href="{{ route('cms.sections.edit', $section) }}">Edit</a>
                    <form method="post" action="{{ route('cms.sections.destroy', $section) }}">
                        @csrf
                        @method('delete')
                        <button class="danger" onclick="return confirm('Hapus section ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted p-6">Belum ada section.</p>
        @endforelse
    </div>

    <div class="p-5">{{ $sections->links() }}</div>
</section>
@endsection
