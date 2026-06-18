<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $credentials['email'])->first();

        if ($user && (! $user->is_active || ($user->locked_until && $user->locked_until->isFuture()))) {
            return back()->withErrors(['email' => 'Akun nonaktif atau terkunci sampai '.$user->locked_until?->format('Y-m-d H:i')])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->user()->forceFill(['failed_login_attempts' => 0, 'locked_until' => null])->save();
            $request->session()->regenerate();

            if ($request->user()->must_change_password) {
                return redirect()->route('password.change')->with('status', 'Silakan ganti password dulu sebelum lanjut.');
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($user) {
            $attempts = $user->failed_login_attempts + 1;
            $user->forceFill([
                'failed_login_attempts' => $attempts,
                'locked_until' => $attempts >= 5 ? now()->addMinutes(15) : null,
            ])->save();
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'sales',
            'is_active' => true,
            'must_change_password' => false,
            'password_changed_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Akun berhasil dibuat. Role default: sales.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            $temporaryPassword = 'Nexora-'.random_int(100000, 999999);
            $user->forceFill([
                'password' => Hash::make($temporaryPassword),
                'must_change_password' => true,
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ])->save();

            return back()->with('temporary_password', $temporaryPassword);
        }

        return back()->with('status', 'Jika email terdaftar, password sementara akan dibuat.');
    }

    public function showChangePassword(): View
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($data['password']),
            'must_change_password' => false,
            'password_changed_at' => now(),
        ])->save();

        return redirect()->route('dashboard')->with('status', 'Password berhasil diganti.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
