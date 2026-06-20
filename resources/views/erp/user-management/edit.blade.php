@extends('layouts.erp', ['activePage' => 'user-management-edit', 'pageTitle' => 'Edit User'])

@section('content')
<section class="section" id="user-edit">
    <div class="card">
        <h2>Edit User: {{ $user->name }}</h2>
        <form method="post" action="{{ route('user-management.update', $user) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $user->name }}" placeholder="Nama" required>
            <input name="email" type="email" value="{{ $user->email }}" placeholder="Email" required>
            <select name="role">
                <option value="admin" @selected($user->role === 'admin')>admin</option>
                <option value="hr" @selected($user->role === 'hr')>hr</option>
                <option value="finance" @selected($user->role === 'finance')>finance</option>
                <option value="sales" @selected($user->role === 'sales')>sales</option>
            </select>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" @checked($user->is_active)>
                Active
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="must_change_password" value="1" @checked($user->must_change_password)>
                Must change password on next login
            </label>
            <button>Update User</button>
        </form>
        <a href="{{ route('user-management.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
