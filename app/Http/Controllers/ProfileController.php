<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('erp.profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $user->id],
            'nik' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:40'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'department' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:120'],
            'emergency_contact' => ['nullable', 'string', 'max:160'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        unset($data['avatar'], $data['current_password'], $data['password']);
        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password')->toString());
            $data['password_changed_at'] = now();
        }
        $user->update($data);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}
