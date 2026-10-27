@extends('layouts.erp', ['activePage' => 'users', 'pageTitle' => 'User Management'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">System Access</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">User Control</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">User Management</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola akun staf, hak akses berbasis role ERP, dan kredensial keamanan.</p>
            </div>
        </div>

        <x-erp.toolbar-actions module="users" :can-create="false" />
    </div>

    {{-- Layout Grid: Form Tambah & Tabel Daftar --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Form Tambah User --}}
        <div class="lg:col-span-4">
            <div class="rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
                <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                    <h2 class="text-sm font-bold text-[#0F172A]">Tambah User Baru</h2>
                    <p class="text-xs text-[#475569]">Buat akun staf baru dan tentukan hak akses sistem.</p>
                </div>

                <form method="post" action="{{ route('user-management.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Nama Lengkap <span class="text-[#DC2626]">*</span></label>
                        <input name="name" class="field-input w-full" placeholder="Nama staf" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Email Perusahaan <span class="text-[#DC2626]">*</span></label>
                        <input name="email" type="email" class="field-input w-full" placeholder="nama@perusahaan.com" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Role Akses <span class="text-[#DC2626]">*</span></label>
                        <select name="role" class="field-input w-full" required>
                            <option value="admin">Admin (Full Access)</option>
                            <option value="hr">HR (Human Resources)</option>
                            <option value="finance">Finance & Accounting</option>
                            <option value="sales">Sales & Commercial</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Password Awal</label>
                        <input name="password" type="password" class="field-input w-full" placeholder="Opsional (default: min 8 karakter)">
                        <p class="mt-1 text-[11px] text-[#475569]">Kosongkan bila ingin menggunakan password default sistem.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary w-full h-10 text-xs font-semibold">
                            Tambah Akun User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Daftar User --}}
        <div class="lg:col-span-8">
            <div class="rounded-lg border border-[#E5E7EB] bg-white shadow-xs overflow-hidden">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] px-5 py-4">
                    <h2 class="text-sm font-bold text-[#0F172A]">Daftar Akun Terdaftar</h2>
                    <span class="text-xs text-[#475569]">Menampilkan {{ $users->count() }} dari {{ $users->total() }} akun</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-folk w-full text-left border-collapse text-xs">
                        <thead class="bg-[#F8FAFC] border-b border-[#E5E7EB] text-[#475569] font-semibold">
                            <tr>
                                <th class="px-5 py-3.5">Nama & Email</th>
                                <th class="px-4 py-3.5">Role</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse($users as $user)
                            <tr class="hover:bg-[#F8FAFC]/60 transition">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-[#0F172A]">{{ $user->name }}</div>
                                    <div class="text-[11px] text-[#475569]">{{ $user->email }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-[#002875]">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $user->is_active ? 'bg-[#F0FDF4] border border-[#86EFAC] text-[#16A34A]' : 'bg-[#FEF2F2] border border-[#FCA5A5] text-[#DC2626]' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <form method="post" action="{{ route('user-management.reset-password', $user) }}">
                                            @csrf @method('patch')
                                            <button type="submit" class="erp-action-button btn btn-outline h-7 px-2.5 text-[11px] font-medium" title="Reset Password">
                                                Reset PW
                                            </button>
                                        </form>
                                        <form method="post" action="{{ route('user-management.destroy', $user) }}">
                                            @csrf @method('delete')
                                            <button data-action="delete" type="submit" class="erp-action-button inline-flex h-7 items-center rounded-[6px] border border-[#FECACA] bg-[#FEF2F2] px-2.5 text-[11px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus User">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-xs text-[#94A3B8]">Belum ada pengguna terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
    </div>

    <x-erp.pagination :paginator="$users" />
        </div>
    </div>
</div>
@endsection
