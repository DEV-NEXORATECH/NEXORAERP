@extends('layouts.app', ['title' => 'Login | NEXORA ERP'])

@section('body')
@component('auth.partials.shell')
    <div class="nx-fade-in">
        <!-- Form Header -->
        <div class="mb-7">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0B2345] tracking-tight">
                Welcome back!
            </h1>
            <p class="mt-1.5 text-sm font-medium text-[#0B2345]/60">
                Secure Login
            </p>
        </div>

        <!-- Notification / Error Messages -->
        @if (session('status'))
            <div class="mb-5 flex items-start gap-3 rounded-[10px] border border-[#0B2345]/15 bg-[#0B2345]/5 px-4 py-3 text-xs sm:text-sm text-[#0B2345]" role="alert">
                <svg class="h-5 w-5 shrink-0 text-[#0B2345]" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="font-medium">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 flex items-start gap-3 rounded-[10px] border border-red-200 bg-red-50/90 px-4 py-3 text-xs sm:text-sm text-red-700" role="alert">
                <svg class="h-5 w-5 shrink-0 text-red-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="font-medium">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.store') }}" id="login-form" data-confirmed="1" class="space-y-4">
            @csrf

            <!-- Email Address Field with Icon -->
            <div>
                <label for="email" class="block mb-1.5 text-xs font-semibold text-[#0B2345] tracking-wide">
                    Email Address
                </label>
                <div class="relative login-field-wrap">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#0B2345]/40 login-field-icon">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email', 'admin@nexora.test') }}" 
                        required 
                        autofocus
                        placeholder="name@company.com"
                        style="padding-left: 4rem !important;"
                        class="w-full h-11 pl-10 pr-4 rounded-[10px] border border-[#0B2345]/15 bg-white text-sm text-[#0B2345] placeholder-[#0B2345]/35 transition-all duration-200 outline-none focus:border-[#0B2345] focus:ring-2 focus:ring-[#0B2345]/10 shadow-[0_1px_2px_rgba(11,35,69,0.03)] login-field-input"
                    >
                </div>
            </div>

            <!-- Password Field with Show/Hide Toggle -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="login-password" class="block text-xs font-semibold text-[#0B2345] tracking-wide">
                        Password
                    </label>
                    <a href="{{ route('password.forgot') }}" class="text-xs font-semibold text-[#0B2345] hover:text-[#0B2345]/80 hover:underline transition-colors duration-150">
                        Forgot Password?
                    </a>
                </div>
                <div class="relative login-field-wrap">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#0B2345]/40 login-field-icon">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input 
                        id="login-password" 
                        name="password" 
                        type="password" 
                        value="password" 
                        required
                        placeholder="••••••••"
                        style="padding-left: 4rem !important; padding-right: 3.25rem !important;"
                        class="w-full h-11 pl-10 pr-11 rounded-[10px] border border-[#0B2345]/15 bg-white text-sm text-[#0B2345] placeholder-[#0B2345]/35 transition-all duration-200 outline-none focus:border-[#0B2345] focus:ring-2 focus:ring-[#0B2345]/10 shadow-[0_1px_2px_rgba(11,35,69,0.03)] login-field-input"
                    >
                    <button 
                        type="button" 
                        id="toggle-password-btn"
                        class="absolute inset-y-0 right-0 w-10 flex items-center justify-center !bg-transparent !p-0 !border-0 !shadow-none text-[#0B2345]/50 hover:text-[#0B2345] transition-colors focus:outline-none cursor-pointer"
                        aria-label="Toggle password visibility"
                    >
                        <!-- Eye Open Icon (default) -->
                        <svg id="eye-icon-open" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <!-- Eye Off Icon (hidden initially) -->
                        <svg id="eye-icon-closed" class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" x2="22" y1="2" y2="22"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button (Dark Blue #0B2345) -->
            <button 
                type="submit" 
                id="login-submit-btn"
                class="w-full h-11 mt-2 rounded-[10px] !bg-[#0B2345] !text-white text-sm font-semibold tracking-wide hover:!bg-[#071932] active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2 shadow-[0_2px_4px_rgba(11,35,69,0.12)] cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed"
            >
                <span id="btn-text">Login</span>
                <!-- Loading Spinner (hidden by default) -->
                <svg id="btn-spinner" class="h-4 w-4 animate-spin hidden text-white" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>

            <!-- Divider -->
            <div class="relative py-2 flex items-center justify-center">
                <div class="w-full border-t border-[#0B2345]/10"></div>
                <span class="absolute bg-white px-3 text-[11px] font-medium tracking-wider text-[#0B2345]/40 uppercase">
                    or
                </span>
            </div>

            <!-- Google SSO Button -->
            <button 
                type="button" 
                onclick="window.location.href='#google-sso'"
                class="w-full h-11 rounded-[10px] !border !border-[#0B2345]/15 !bg-white text-sm font-semibold !text-[#0B2345] hover:!bg-[#0B2345]/[0.02] hover:!border-[#0B2345]/30 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2.5 shadow-[0_1px_2px_rgba(11,35,69,0.03)] cursor-pointer"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Login with Google (SSO)</span>
            </button>
        </form>

        <!-- Register Switch -->
        <div class="mt-6 text-center text-xs text-[#0B2345]/70">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="font-bold text-[#0B2345] hover:underline ml-1">
                Register
            </a>
        </div>
    </div>
@endcomponent

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Password Visibility Toggle
        const passwordInput = document.getElementById('login-password');
        const toggleBtn = document.getElementById('toggle-password-btn');
        const eyeOpen = document.getElementById('eye-icon-open');
        const eyeClosed = document.getElementById('eye-icon-closed');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (eyeOpen && eyeClosed) {
                    eyeOpen.classList.toggle('hidden', isPassword);
                    eyeClosed.classList.toggle('hidden', !isPassword);
                }
            });
        }

        // Form Submit Spinner Feedback
        const loginForm = document.getElementById('login-form');
        const submitBtn = document.getElementById('login-submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                if (btnText) btnText.textContent = 'Memproses...';
                if (btnSpinner) btnSpinner.classList.remove('hidden');
            });
        }
    });
</script>
@endpush
@endsection
