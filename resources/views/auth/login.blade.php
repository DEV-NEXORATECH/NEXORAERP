@extends('layouts.app', ['title' => 'Login NEXORA ERP'])

@section('body')
@component('auth.partials.shell')
    <div class="auth-heading">
        <h1>Welcome back!</h1>
        <p>Secure Login</p>
    </div>

    @if ($errors->any())
        <div class="errors">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('login.store') }}" class="auth-form">
        @csrf
        <div>
            <label>Email Address</label>
            <input name="email" type="email" value="{{ old('email', 'admin@nexora.test') }}" required autofocus>
        </div>

        <div>
            <label>Password</label>
            <div class="auth-password">
                <input id="login-password" name="password" type="password" value="password" required>
                <button type="button" data-password-toggle="login-password" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <a class="auth-forgot" href="{{ route('password.forgot') }}">Forgot Password?</a>

        <button class="auth-primary">Login</button>

        <button class="auth-sso" type="button">
            <span>G</span>
            Login with Google (SSO)
        </button>
    </form>

    <div class="auth-switch">
        <span>Don't have an account?</span>
        <a href="{{ route('register') }}">Register</a>
    </div>
@endcomponent
@endsection
