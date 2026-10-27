@extends('layouts.erp', ['activePage' => 'profile', 'pageTitle' => 'View Account'])

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <div class="flex items-center justify-between gap-3">
        <div><p class="text-xs font-bold uppercase tracking-widest text-slate-500">Account settings</p><h1 class="text-2xl font-black text-[#0B2345]">View Account</h1><p class="text-sm text-slate-500">Kelola identitas, kontak, jabatan, dan keamanan akun Anda.</p></div>
    </div>
    @if(session('status'))<div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('put')
        <section class="rounded-xl border border-[#D1D5DB] bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-center gap-4">
                @if($user->avatar_path)<img src="{{ asset('storage/'.$user->avatar_path) }}" class="h-20 w-20 rounded-full object-cover" alt="Foto profil">@else<div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#0B2345] text-2xl font-black text-white">{{ strtoupper(substr($user->name,0,2)) }}</div>@endif
                <div><h2 class="font-bold text-[#0B2345]">Foto Profil</h2><p class="text-xs text-slate-500">PNG, JPG atau WEBP, maksimal 2 MB.</p><input type="file" name="avatar" accept="image/*" class="mt-2 text-xs"></div>
            </div>
            <h2 class="mb-4 border-b border-slate-100 pb-3 font-bold text-[#0B2345]">Informasi Utama</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach([['name','Nama Lengkap'],['email','Email'],['nik','NIK'],['phone','Nomor Telepon'],['job_title','Jabatan'],['department','Departemen'],['birth_place','Tempat Lahir'],['birth_date','Tanggal Lahir']] as [$field,$label])
                    <label class="grid gap-1.5 text-sm font-semibold text-slate-700">{{ $label }}<input name="{{ $field }}" type="{{ $field === 'birth_date' ? 'date' : ($field === 'email' ? 'email' : 'text') }}" value="{{ old($field, $user->{$field}) }}" class="w-full rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm outline-none focus:border-[#0B2345] focus:ring-2 focus:ring-[#0B2345]/10"></label>
                @endforeach
            </div>
            <label class="mt-4 grid gap-1.5 text-sm font-semibold text-slate-700">Alamat<input name="address" value="{{ old('address', $user->address) }}" class="w-full rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm outline-none focus:border-[#0B2345]"></label>
            <label class="mt-4 grid gap-1.5 text-sm font-semibold text-slate-700">Kontak Darurat<input name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" placeholder="Nama dan nomor kontak darurat" class="w-full rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm outline-none focus:border-[#0B2345]"></label>
        </section>
        <section class="rounded-xl border border-[#D1D5DB] bg-white p-5 shadow-sm"><h2 class="mb-4 border-b border-slate-100 pb-3 font-bold text-[#0B2345]">Ganti Password</h2><div class="grid gap-4 md:grid-cols-3"><label class="grid gap-1.5 text-sm font-semibold text-slate-700">Password Saat Ini<input name="current_password" type="password" class="rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm"></label><label class="grid gap-1.5 text-sm font-semibold text-slate-700">Password Baru<input name="password" type="password" class="rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm"></label><label class="grid gap-1.5 text-sm font-semibold text-slate-700">Konfirmasi Password<input name="password_confirmation" type="password" class="rounded-lg border border-[#D1D5DB] px-3 py-2.5 text-sm"></label></div><p class="mt-3 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti password.</p></section>
        <div class="flex justify-end"><button class="rounded-lg bg-[#0B2345] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#163b6d]">Simpan Perubahan</button></div>
    </form>
</div>
@endsection
