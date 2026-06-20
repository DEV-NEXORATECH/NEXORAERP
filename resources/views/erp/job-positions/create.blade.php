@extends('layouts.erp', ['activePage' => 'job-positions-create-page', 'pageTitle' => 'Tambah Job Position'])

@section('content')
<section class="section" id="job-position-create">
    <div class="card">
        <h2>Tambah Job Position</h2>
        <form method="post" action="{{ route('job-positions.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama position" required>
            <button>Tambah Position</button>
        </form>
        <a href="{{ route('job-positions.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
