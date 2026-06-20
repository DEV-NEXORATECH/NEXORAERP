@extends('layouts.erp', ['activePage' => 'modules', 'pageTitle' => $sectionName . ' Module'])

@section('content')
@php
    $hubIcons = [
        'Finance' => 'cashflow',
        'HR' => 'employees',
        'Procurement' => 'projects',
        'Admin' => 'settings',
        'Reports' => 'reports',
        'Sales' => 'proposal',
        'Main' => 'dashboard',
    ];
@endphp

<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('modules.index') }}">Back to Hub</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon($hubIcons[$sectionName] ?? 'dashboard') !!}</span>
                <div>
                    <span class="module-eyebrow">NEXORA ERP Module</span>
                    <h1>{{ $sectionName }} Hub</h1>
                </div>
            </div>
            <p>Kelola dan buka seluruh sub modul {{ $sectionName }} dari satu halaman yang ringkas.</p>
        </div>
        <div class="module-count">
            <strong>{{ count($items) }}</strong>
            <span>Sub Module</span>
        </div>
    </div>

    <div class="module-sub-grid">
        @foreach($items as $item)
            <a href="{{ route($item['route']) }}" class="module-sub-card">
                <div class="module-sub-main">
                    <span class="module-sub-icon">{!! $icon($item['icon']) !!}</span>
                    <div>
                        <h3>{{ $item['label'] }}</h3>
                        <p>Buka dan kelola data {{ $item['label'] }}</p>
                    </div>
                </div>
                <span class="module-sub-action">Open</span>
            </a>
        @endforeach
    </div>
</section>
@endsection
