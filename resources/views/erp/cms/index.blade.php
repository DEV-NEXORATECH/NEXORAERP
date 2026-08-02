@extends('layouts.erp', ['activePage' => 'cms', 'pageTitle' => 'CMS'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('admin.index') }}">Back to Admin</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('cms') !!}</span>
                <div>
                    <span class="module-eyebrow">NEXORA CMS</span>
                    <h1>CMS API Control</h1>
                </div>
            </div>
            <p>Pilih menu CMS yang mau diatur. Konten tidak dibuat jadi landing page; semuanya disiapkan untuk API.</p>
        </div>
        <div class="module-count">
            <strong>{{ $sectionCount + $postCount }}</strong>
            <span>CMS Item</span>
        </div>
    </div>
</section>

<section class="cms-grid">
    <a class="cms-tile group" href="{{ route('cms.sections.index') }}">
        <span class="cms-tile-icon">{!! $icon('content') !!}</span>
        <h2>Section Content</h2>
        <p>CRUD section konten API seperti hero, about, services, contact, dan custom section.</p>
        <div class="cms-tile-foot">
            <span class="cms-tile-count">{{ $sectionCount }} item</span>
            <span class="cms-tile-action">OPEN</span>
        </div>
    </a>

    <a class="cms-tile group" href="{{ route('cms.blog.index') }}">
        <span class="cms-tile-icon">{!! $icon('blog') !!}</span>
        <h2>Blog / Article</h2>
        <p>CRUD artikel CMS untuk endpoint blog API. Draft dan published tetap dipisah statusnya.</p>
        <div class="cms-tile-foot">
            <span class="cms-tile-count">{{ $postCount }} item</span>
            <span class="cms-tile-action">OPEN</span>
        </div>
    </a>

    <a class="cms-tile group" href="{{ route('cms.api-settings') }}">
        <span class="cms-tile-icon">{!! $icon('api') !!}</span>
        <h2>API Settings</h2>
        <p>Lihat daftar endpoint CMS API yang dipakai frontend/aplikasi lain.</p>
        <div class="cms-tile-foot">
            <span class="cms-tile-count">API</span>
            <span class="cms-tile-action">OPEN</span>
        </div>
    </a>
</section>
@endsection
