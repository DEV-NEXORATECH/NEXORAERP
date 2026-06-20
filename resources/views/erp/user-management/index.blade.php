@extends('layouts.erp', ['activePage' => 'user-management', 'pageTitle' => 'User Management'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="user-management">
    <div class="section-head">
        <h2>Daftar User</h2>
        @if($can('admin', 'finance') || $can('admin'))
        <a href="{{ route('user-management.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('user-management.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Role</option>
            <option value="admin" @selected(request('type') == 'admin')>Admin</option>
            <option value="finance" @selected(request('type') == 'finance')>Finance</option>
            <option value="hr" @selected(request('type') == 'hr')>HR</option>
            <option value="sales" @selected(request('type') == 'sales')>Sales</option>
        </select>
        <select name="is_active" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Status</option>
            <option value="1" @selected(request('is_active') == '1')>Active</option>
            <option value="0" @selected(request('is_active') == '0')>Inactive</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('user-management.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

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
                    <a href="{{ route('user-management.edit-page', $user) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('user-management.reset-password', $user) }}" class="inline">@csrf @method('patch')<button class="mini ghost">Reset PW</button></form>
                    <form method="post" action="{{ route('user-management.destroy', $user) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}</span>
        {{ $users->links() }}
    </div>
    @endif
</section>
@endsection
