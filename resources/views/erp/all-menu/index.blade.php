@extends('layouts.erp', ['activePage' => request()->routeIs('modules.index') ? 'modules' : 'all-menu', 'pageTitle' => request()->routeIs('modules.index') ? 'Module' : 'All Module'])

@section('content')
@php
    $hubOrder = ['Finance', 'HR', 'Procurement', 'Admin', 'Reports', 'Sales', 'Main'];
    $hubNames = [
        'Finance' => 'Finance Hub',
        'HR' => 'HR Hub',
        'Procurement' => 'Procurement Hub',
        'Admin' => 'Admin Hub',
        'Reports' => 'Report Hub',
        'Sales' => 'Sales Hub',
        'Main' => 'Dashboard Hub',
    ];
    $hubIcons = [
        'Finance' => 'cashflow',
        'HR' => 'employees',
        'Procurement' => 'projects',
        'Admin' => 'settings',
        'Reports' => 'reports',
        'Sales' => 'proposal',
        'Main' => 'dashboard',
    ];
    $positions = [
        0 => 'hub-top-right',
        1 => 'hub-right',
        2 => 'hub-bottom-right',
        3 => 'hub-bottom',
        4 => 'hub-bottom-left',
        5 => 'hub-left',
        6 => 'hub-top-left',
    ];
    $orderedMenus = collect($hubOrder)
        ->filter(fn ($section) => isset($menus[$section]))
        ->mapWithKeys(fn ($section) => [$section => $menus[$section]]);
@endphp

<section class="process-hub-page">
    <div class="process-hub-heading">
        <span>ERP Workflow Map</span>
        <h1>Company Process Hub</h1>
        <p>Seluruh modul ERP NEXORA ditata sebagai alur kerja perusahaan yang saling terhubung.</p>
    </div>

    <div class="process-hub">

        @foreach($orderedMenus as $section => $items)
            <div class="process-node {{ $positions[$loop->index] ?? '' }}">
                <a href="{{ route('modules.show', str($section)->slug()) }}" class="process-node-card">
                    <div class="process-node-icon">{!! $icon($hubIcons[$section] ?? 'dashboard') !!}</div>
                    <div>
                        <h3>{{ $hubNames[$section] ?? $section }}</h3>
                        <p>{{ count($items) }} menu</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>
@endsection
