@extends('layouts.erp', ['activePage' => 'user-management', 'pageTitle' => 'User Management'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Manajemen Pengguna & Otorisasi"
        subtitle="Kelola akun staf pengguna, penetapan peran (role), dan kontrol keamanan akses ERP."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'User Management']]"
    >
        <x-slot:actions>
            @if($can('admin'))
                <a href="{{ route('user-management.create-page') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Pengguna
                </a>
            @endif
        </x-slot:actions>
    </x-erp.page-header>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-xs">
        <form method="get" action="{{ route('user-management.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
            </div>
            <div>
                <select name="type" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('type') == 'admin')>Admin</option>
                    <option value="finance" @selected(request('type') == 'finance')>Finance</option>
                    <option value="hr" @selected(request('type') == 'hr')>HR</option>
                    <option value="sales" @selected(request('type') == 'sales')>Sales</option>
                </select>
            </div>
            <div>
                <select name="is_active" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('is_active') == '1')>Aktif</option>
                    <option value="0" @selected(request('is_active') == '0')>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors">
                    Filter
                </button>
                @if(request('search') || request('type') || request('is_active') !== null)
                    <a href="{{ route('user-management.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Role / Hak Akses</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status Akun</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 text-slate-900">
                                <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 font-mono">{{ $user->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $roleBadge = match(strtolower($user->role)) {
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'finance' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'sales' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'hr' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $roleBadge }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($can('admin'))
                                        <a href="{{ route('user-management.edit-page', $user) }}" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 hover:text-[#002875] transition-colors" title="Edit User">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('user-management.reset-password', $user) }}" class="inline" onsubmit="return confirm('Reset password pengguna ini ke default?');">
                                            @csrf
                                            @method('patch')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50 transition-colors" title="Reset Password">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                Reset
                                            </button>
                                        </form>
                                        <form method="post" action="{{ route('user-management.destroy', $user) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-red-200 bg-white px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                <x-erp.empty-state message="Belum ada data akun pengguna." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                <span>Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} pengguna</span>
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
