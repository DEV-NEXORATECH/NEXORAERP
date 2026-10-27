@extends('layouts.erp', ['activePage' => 'user-management', 'pageTitle' => 'Edit User'])

@section('content')
<div class="erp-form-page space-y-5 max-w-2xl">
    <x-erp.page-header
        title="Edit Pengguna Sistem"
        subtitle="Perbarui data akun, role akses, dan status login: {{ $user->name }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'User Management', 'url' => route('user-management.index')], ['label' => 'Edit']]"
    />

    <div class="rounded-lg border border-[#D1D5DB] bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('user-management.update', $user) }}" class="space-y-5">
            @csrf
            @method('put')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email Login <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Hak Akses / Role <span class="text-red-500">*</span>
                </label>
                <select name="role" required
                    class="w-full rounded-md border border-[#D1D5DB] bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="admin" @selected($user->role === 'admin')>Administrator (Full Access)</option>
                    <option value="finance" @selected($user->role === 'finance')>Finance & Accounting</option>
                    <option value="hr" @selected($user->role === 'hr')>Human Resources (HR)</option>
                    <option value="sales" @selected($user->role === 'sales')>Sales & CRM</option>
                </select>
            </div>

            <div class="space-y-3 pt-2">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" @checked($user->is_active)
                        class="h-4 w-4 rounded border-[#D1D5DB] text-[#002875] focus:ring-[#002875]">
                    <span class="text-xs font-medium text-slate-800">Status Akun Aktif (Dapat Login)</span>
                </label>

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="must_change_password" value="1" @checked($user->must_change_password)
                        class="h-4 w-4 rounded border-[#D1D5DB] text-[#002875] focus:ring-[#002875]">
                    <span class="text-xs font-medium text-slate-800">Wajib Ganti Password Pada Login Berikutnya</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Pengguna
                </button>
                <a href="{{ route('user-management.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#F3F4F6] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
