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
        <a class="button ghost" href="{{ route('modules.index') }}">Back to Hub</a>
        <div class="module-detail-icon">{!! $icon($hubIcons[$sectionName] ?? 'dashboard') !!}</div>
        <h1>{{ $sectionName }} Hub</h1>
        <p>Pilih sub modul {{ $sectionName }} yang ingin dibuka.</p>
        <span class="badge">{{ count($items) }} sub module</span>
    </div>

    <div class="grid three section">
        @foreach($items as $item)
            <a href="{{ route($item['route']) }}" class="module-sub-card">
                <span>{!! $icon($item['icon']) !!}</span>
                <div>
                    <h3>{{ $item['label'] }}</h3>
                    <p>Buka halaman {{ $item['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endsection
