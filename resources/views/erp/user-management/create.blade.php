@extends('layouts.erp', ['activePage' => 'user-management', 'pageTitle' => 'Tambah User'])

@section('content')
<div class="space-y-5 max-w-2xl">
    <x-erp.page-header
        title="Tambah Pengguna Sistem"
        subtitle="Buat akun pengguna baru dengan hak akses role dan otentikasi aman."
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Administration', 'url' => route('admin.index')], ['label' => 'User Management', 'url' => route('user-management.index')], ['label' => 'Tambah User']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('user-management.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama staf pengguna..." required autofocus
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email Login <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="user@nexora.co.id" required
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 placeholder-slate-400 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Hak Akses / Role <span class="text-red-500">*</span>
                </label>
                <select name="role" required
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    <option value="admin" @selected(old('role') === 'admin')>Administrator (Full Access)</option>
                    <option value="finance" @selected(old('role') === 'finance')>Finance & Accounting</option>
                    <option value="hr" @selected(old('role') === 'hr')>Human Resources (HR)</option>
                    <option value="sales" @selected(old('role') === 'sales')>Sales & CRM</option>
                </select>
                @error('role')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi Awal (Password)
                </label>
                <input type="password" name="password" placeholder="Minimal 8 karakter (kosongkan jika auto-generated)..."
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                <span class="text-[11px] text-slate-500 mt-1 block">Jika dikosongkan, kata sandi default aman akan dibuat secara otomatis.</span>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Simpan Pengguna
                </button>
                <a href="{{ route('user-management.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
