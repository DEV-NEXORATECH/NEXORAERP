@extends('layouts.app', ['title' => 'Forgot Password'])

@section('body')
@component('auth.partials.shell')
    <div class="auth-heading">
        <h1>Reset password</h1>
        <p>Buat password sementara untuk email terdaftar.</p>
    </div>

    @if (session('temporary_password'))
        <div class="notice">Password sementara: <strong>{{ session('temporary_password') }}</strong></div>
    @endif

    @if ($errors->any())
        <div class="errors">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('password.forgot.store') }}" class="auth-form">
        @csrf
        <div>
            <label>Email Address</label>
            <input name="email" type="email" required>
        </div>
        <button class="auth-primary">Buat Password Sementara</button>
    </form>

    <div class="auth-switch">
        <a href="{{ route('login') }}">Kembali login</a>
    </div>
@endcomponent
@endsection
