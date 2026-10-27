@extends('layouts.erp', ['activePage' => 'user-management', 'pageTitle' => 'User Management'])

@section('content')
<div class="space-y-4">
    <x-erp.page-header
        title="Manajemen Pengguna & Otorisasi"
        subtitle="Kelola akun staf pengguna, penetapan peran (role), dan kontrol keamanan akses ERP."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'User Management']]"
    >
        <x-slot:actions>
            <x-erp.toolbar-actions
                module="users"
                :create-route="route('user-management.create-page')"
                create-label="+ Tambah Pengguna"
                :can-create="$can('admin')"
            />
        </x-slot:actions>
    </x-erp.page-header>

    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <div class="filter-panel-title">
                <div class="filter-panel-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <h3>Filter & Pencarian</h3>
            </div>
        </div>
        <form method="get" action="{{ route('user-management.index') }}" class="filter-toolbar">
            <div class="relative w-64 flex-shrink-0">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
                    class="filter-search pl-8 pr-3 text-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-[#9CA3AF]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>
            <select name="type" class="text-xs">
                <option value="">Semua Role</option>
                <option value="admin" @selected(request('type') == 'admin')>Admin</option>
                <option value="finance" @selected(request('type') == 'finance')>Finance</option>
                <option value="hr" @selected(request('type') == 'hr')>HR</option>
                <option value="sales" @selected(request('type') == 'sales')>Sales</option>
            </select>
            <select name="is_active" class="text-xs">
                <option value="">Semua Status</option>
                <option value="1" @selected(request('is_active') == '1')>Aktif</option>
                <option value="0" @selected(request('is_active') == '0')>Nonaktif</option>
            </select>
            <button type="submit" class="inline-flex h-[34px] items-center gap-1.5 rounded-md bg-[#002875] px-3.5 text-xs font-semibold text-white hover:bg-[#001B50] transition-colors flex-shrink-0 cursor-pointer">
                Filter
            </button>
            @if(request('search') || request('type') || request('is_active') !== null)
                <a href="{{ route('user-management.index') }}" class="inline-flex h-[34px] items-center rounded-md border border-[#D1D5DB] bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors flex-shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Card 3 — Workspace: Data Table + Pagination --}}
    <div class="workspace-card rounded-lg border border-[#D1D5DB] bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-[#D1D5DB]">
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Role / Hak Akses</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider">Status Akun</th>
                        <th class="px-4 py-3 font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#F3F4F6]/70 transition-colors">
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
                                        default => 'bg-slate-50 text-slate-700 border-[#D1D5DB]'
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
                                        <a href="{{ route('user-management.edit-page', $user) }}" class="inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] hover:text-[#002875] transition-colors" title="Edit User">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('user-management.reset-password', $user) }}" class="inline" class="inline erp-action-form" data-confirm-type="default" data-confirm-title="Konfirmasi" data-confirm-text="Reset password pengguna ini ke default?">
                                            @csrf
                                            @method('patch')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded border border-[#D1D5DB] bg-white px-2 py-1 text-[11px] font-medium text-slate-700 hover:bg-[#F3F4F6] transition-colors" title="Reset Password">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                Reset
                                            </button>
                                        </form>
                                        <form method="post" action="{{ route('user-management.destroy', $user) }}" class="inline" class="inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus data" data-confirm-text="Apakah Anda yakin ingin menghapus akun pengguna ini?">
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
    </div>

    {{-- Card 4 — Pagination (Card Terpisah) --}}
    <x-erp.pagination :paginator="$users" />
</div>

<x-erp.import-modal module="users" title="Impor Data Pengguna" />
@endsection

