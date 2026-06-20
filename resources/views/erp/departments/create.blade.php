@extends('layouts.erp', ['activePage' => 'departments-create-page', 'pageTitle' => 'Tambah Department'])

@section('content')
<section class="section" id="department-create">
    <div class="card">
        <h2>Tambah Department</h2>
        <form method="post" action="{{ route('departments.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama department" required>
            <button>Tambah Department</button>
        </form>
        <a href="{{ route('departments.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
