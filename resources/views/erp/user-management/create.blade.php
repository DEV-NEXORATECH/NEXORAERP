@extends('layouts.erp', ['activePage' => 'user-management-create', 'pageTitle' => 'Tambah User'])

@section('content')
<section class="section" id="user-create">
    <div class="card">
        <h2>Tambah User</h2>
        <form method="post" action="{{ route('user-management.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama" required>
            <input name="email" type="email" placeholder="Email" required>
            <select name="role">
                <option value="admin">admin</option>
                <option value="hr">hr</option>
                <option value="finance">finance</option>
                <option value="sales">sales</option>
            </select>
            <input name="password" placeholder="Password optional, min 8">
            <button>Tambah User</button>
        </form>
        <a href="{{ route('user-management.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
