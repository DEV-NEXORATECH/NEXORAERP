@extends('layouts.erp', ['activePage' => 'users', 'pageTitle' => 'User Management'])

@section('content')
<section class="grid two section" id="users">
    <div class="card">
        <h2>Tambah User</h2>
        <form method="post" action="{{ route('users.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama" required>
            <input name="email" type="email" placeholder="Email" required>
            <select name="role"><option>admin</option><option>hr</option><option>finance</option><option>sales</option></select>
            <input name="password" placeholder="Password optional, min 8">
            <button>Tambah User</button>
        </form>
    </div>
    <div class="card wide">
        <h2>Daftar User</h2>
        <table>
            <thead><tr><th>Nama</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="font-bold">{{ $user->name }}</div>
                        <div class="muted">{{ $user->email }}</div>
                    </td>
                    <td><span class="badge">{{ $user->role }}</span></td>
                    <td><span class="badge badge-{{ $user->is_active ? 'active' : 'hold' }}">{{ $user->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="actions">
                        <form method="post" action="{{ route('users.reset-password', $user) }}">@csrf @method('patch')<button class="mini ghost">Reset PW</button></form>
                        <form method="post" action="{{ route('users.destroy', $user) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
