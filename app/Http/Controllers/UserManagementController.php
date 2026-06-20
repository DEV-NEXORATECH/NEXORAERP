<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $users = $this->applyListFilters(User::orderBy('name'), $request, ['name', 'email'])->paginate(15)->withQueryString();

        if ($request->filled('is_active')) {
            $users->where('is_active', $request->boolean('is_active'));
        }

        return view('erp.user-management.index', compact('users'));
    }

    public function create(): View
    {
        return view('erp.user-management.create');
    }

    public function edit(User $user): View
    {
        return view('erp.user-management.edit', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'password' => ['nullable', 'min:8'],
        ]);

        $plain = $data['password'] ?: 'Nexora-' . random_int(100000, 999999);
        $user  = User::create([
            'name'                => $data['name'],
            'email'               => $data['email'],
            'role'                => $data['role'],
            'password'            => Hash::make($plain),
            'must_change_password' => true,
            'is_active'           => true,
        ]);
        $this->audit('created', $user, 'User dibuat');

        return redirect()->route('user-management.index')->with('temporary_password', 'Password user baru: ' . $plain);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'                 => ['required', 'max:255'],
            'email'                => ['required', 'email', Rule::unique('users', 'email')->ignore($user)],
            'role'                 => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'is_active'            => ['nullable', 'boolean'],
            'must_change_password' => ['nullable', 'boolean'],
        ]);

        $old = $user->toArray();
        $user->update($data + ['is_active' => false, 'must_change_password' => false]);
        $this->audit('updated', $user, 'User diupdate', $old, $user->fresh()->toArray());

        return redirect()->route('user-management.index')->with('status', 'User berhasil diupdate.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 422, 'Tidak bisa hapus user yang sedang login.');
        $this->audit('deleted', $user, 'User dihapus', $user->toArray());
        $user->delete();

        return redirect()->route('user-management.index')->with('status', 'User berhasil dinonaktifkan/dihapus.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $plain = 'Nexora-' . random_int(100000, 999999);
        $user->forceFill([
            'password'              => Hash::make($plain),
            'must_change_password'  => true,
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ])->save();
        $this->audit('password_reset', $user, 'Password user direset');

        return redirect()->route('user-management.index')->with('temporary_password', 'Password sementara: ' . $plain);
    }
}
