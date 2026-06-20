@extends('layouts.erp', ['activePage' => request()->routeIs('modules.index') ? 'modules' : 'all-menu', 'pageTitle' => request()->routeIs('modules.index') ? 'Module' : 'All Module'])

@section('content')
@php
    $hubOrder = ['Main', 'Reports', 'Finance', 'HR', 'Sales', 'Procurement'];
    $hubNames = [
        'Finance' => 'Finance Hub',
        'HR' => 'HR Hub',
        'Procurement' => 'Procurement Hub',
        'Reports' => 'Report Hub',
        'Sales' => 'Sales Hub',
        'Main' => 'Dashboard Hub',
    ];
    $hubIcons = [
        'Finance' => 'cashflow',
        'HR' => 'employees',
        'Procurement' => 'projects',
        'Reports' => 'reports',
        'Sales' => 'proposal',
        'Main' => 'dashboard',
    ];
    $positions = [
        'Main' => 'hub-top-left',
        'Reports' => 'hub-top-center',
        'Finance' => 'hub-top-right',
        'Sales' => 'hub-bottom-left',
        'HR' => 'hub-bottom-center',
        'Procurement' => 'hub-bottom-right',
    ];
    $orderedMenus = collect($hubOrder)
        ->filter(fn ($section) => isset($menus[$section]))
        ->mapWithKeys(fn ($section) => [$section => $menus[$section]]);
@endphp

<section class="process-hub-page">
    <div class="process-hub-heading">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('dashboard') }}">Dashboard</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('list') !!}</span>
                <div>
                    <span class="module-eyebrow">ERP Workflow Map</span>
                    <h1>Company Process Hub</h1>
                </div>
            </div>
            <p>Seluruh modul ERP NEXORA ditata sebagai alur kerja perusahaan yang saling terhubung.</p>
        </div>
        <div class="process-hub-count">
            <strong>{{ $orderedMenus->count() }}</strong>
            <span>Hub Module</span>
        </div>
    </div>

    <div class="module-sub-grid">

        @foreach($orderedMenus as $section => $items)
            <a href="{{ route('modules.show', str($section)->slug()) }}" class="module-sub-card">
                <div class="module-sub-main">
                    <span class="module-sub-icon">{!! $icon($hubIcons[$section] ?? 'dashboard') !!}</span>
                    <div>
                        <h3>{{ $hubNames[$section] ?? $section }}</h3>
                        <p>{{ count($items) }} menu tersedia di {{ $hubNames[$section] ?? $section }}.</p>
                    </div>
                </div>
                <span class="module-sub-action">Open</span>
            </a>
        @endforeach
    </div>
</section>
@endsection
