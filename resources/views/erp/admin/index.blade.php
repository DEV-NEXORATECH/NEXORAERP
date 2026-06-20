@extends('layouts.erp', ['activePage' => 'company-settings', 'pageTitle' => 'Settings Admin'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('dashboard') }}">Dashboard</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('settings') !!}</span>
                <div>
                    <span class="module-eyebrow">NEXORA ERP Admin</span>
                    <h1>Settings Admin</h1>
                </div>
            </div>
            <p>Kelola konfigurasi dasar ERP, user, master data, audit, trash, dan backup dari satu halaman admin.</p>
        </div>
        <div class="module-count">
            <strong>{{ count($items) }}</strong>
            <span>Menu</span>
        </div>
    </div>
</section>

<section class="module-sub-grid">
    @foreach($items as $item)
        <a href="{{ route($item['route']) }}" class="module-sub-card">
            <div class="module-sub-main">
                <span class="module-sub-icon">{!! $icon($item['icon']) !!}</span>
                <div>
                    <h3>{{ $item['label'] }}</h3>
                    <p>{{ $item['desc'] }}</p>
                </div>
            </div>
            <span class="module-sub-action">Open</span>
        </a>
    @endforeach
</section>
@endsection
