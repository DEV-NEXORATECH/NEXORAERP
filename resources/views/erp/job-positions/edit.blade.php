@extends('layouts.erp', ['activePage' => 'job-positions-edit-page', 'pageTitle' => 'Edit Job Position'])

@section('content')
<section class="section" id="job-position-edit">
    <div class="card">
        <h2>Edit Job Position: {{ $jobPosition->name }}</h2>
        <form method="post" action="{{ route('job-positions.update', $jobPosition) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $jobPosition->name }}" placeholder="Nama position" required>
            <button>Update Position</button>
        </form>
        <a href="{{ route('job-positions.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
