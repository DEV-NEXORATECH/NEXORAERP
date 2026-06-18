@extends('layouts.app', ['title' => 'Register NEXORA ERP'])

@section('body')
@component('auth.partials.shell')
    <div class="auth-heading">
        <h1>Create account</h1>
        <p>Register NEXORA ERP</p>
    </div>

    @if ($errors->any())
        <div class="errors">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('register.store') }}" class="auth-form">
        @csrf
        <div>
            <label>Full Name</label>
            <input name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label>Email Address</label>
            <input name="email" type="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Password</label>
            <div class="auth-password">
                <input id="register-password" name="password" type="password" required>
                <button type="button" data-password-toggle="register-password" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <label>Confirm Password</label>
            <div class="auth-password">
                <input id="register-password-confirmation" name="password_confirmation" type="password" required>
                <button type="button" data-password-toggle="register-password-confirmation" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button class="auth-primary">Register</button>
    </form>

    <div class="auth-switch">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}">Login</a>
    </div>
@endcomponent
@endsection
