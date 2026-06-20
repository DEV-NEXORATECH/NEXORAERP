@extends('layouts.erp', ['activePage' => 'departments-edit-page', 'pageTitle' => 'Edit Department'])

@section('content')
<section class="section" id="department-edit">
    <div class="card">
        <h2>Edit Department: {{ $department->name }}</h2>
        <form method="post" action="{{ route('departments.update', $department) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $department->name }}" placeholder="Nama department" required>
            <button>Update Department</button>
        </form>
        <a href="{{ route('departments.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
