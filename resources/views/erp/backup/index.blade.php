@extends('layouts.erp', ['activePage' => 'backup', 'pageTitle' => 'Backup Database'])

@section('content')
<section class="section" id="backup">
    <div class="card">
        <h2>Backup Database</h2>
        <p class="text-sm text-slate-500 mb-4">Download database SQLite untuk cadangan.</p>
        <a href="{{ route('backup.download') }}" class="btn">Download Backup</a>
    </div>
</section>
@endsection
