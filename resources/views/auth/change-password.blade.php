@extends('layouts.app', ['title' => 'Change Password'])

@section('body')
@component('auth.partials.shell')
    <div class="auth-heading">
        <h1>Change password</h1>
        <p>Amankan akun sebelum lanjut ke dashboard.</p>
    </div>

    @if (session('status')) <div class="notice">{{ session('status') }}</div> @endif
    @if ($errors->any()) <div class="errors">{{ $errors->first() }}</div> @endif

    <form method="post" action="{{ route('password.change.store') }}" class="auth-form">
        @csrf
        <div><label>Password Saat Ini</label><input name="current_password" type="password" required></div>
        <div><label>Password Baru</label><input name="password" type="password" required></div>
        <div><label>Konfirmasi Password Baru</label><input name="password_confirmation" type="password" required></div>
        <button class="auth-primary">Simpan Password</button>
    </form>
@endcomponent
@endsection
