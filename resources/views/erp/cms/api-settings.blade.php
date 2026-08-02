@extends('layouts.erp', ['activePage' => 'cms-api-settings', 'pageTitle' => 'CMS API Settings'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('cms.index') }}">Back to CMS</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('api') !!}</span>
                <div>
                    <span class="module-eyebrow">CMS API</span>
                    <h1>API Settings</h1>
                </div>
            </div>
            <p>Daftar endpoint CMS API. Ini bukan landing page, cuma referensi API untuk frontend/aplikasi lain.</p>
        </div>
    </div>
</section>

<section class="cms-panel">
    <div class="cms-panel-head">
        <div>
            <h2>Endpoint CMS API</h2>
            <p>Endpoint yang dipakai frontend/aplikasi lain untuk baca dan kelola konten CMS.</p>
        </div>
    </div>
    <div class="cms-list">
        @foreach($endpoints as $endpoint)
            <div class="cms-endpoint">
                <span class="cms-method cms-method-{{ strtolower($endpoint['method']) }}">{{ $endpoint['method'] }}</span>
                <div>
                    <h3>{{ $endpoint['name'] }}</h3>
                    <p class="cms-path">{{ $endpoint['path'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
