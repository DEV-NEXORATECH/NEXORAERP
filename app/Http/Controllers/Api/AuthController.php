<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Account is deactivated.'], 403);
        }

        $token = $user->createToken($request->device_name ?? 'api-token')->plainTextToken;

        return $this->respond([
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
            'company_code' => 'required|string|max:20',
            'device_name' => 'nullable|string|max:255',
        ]);

        $companyCode = strtoupper(trim($data['company_code']));

        $company = Company::whereRaw('UPPER(code) = ?', [$companyCode])
            ->where('is_active', true)
            ->first();

        if (!$company) {
            throw ValidationException::withMessages([
                'company_code' => ['Company code is invalid or inactive.'],
            ]);
        }

        if ($company->access_type !== 'external') {
            throw ValidationException::withMessages([
                'company_code' => ['Internal company code cannot be used for public Command Center registration.'],
            ]);
        }

        $user = User::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'client',
            'is_active' => true,
            'must_change_password' => false,
            'password_changed_at' => now(),
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken($request->device_name ?? 'api-token')->plainTextToken;

        return $this->respond([
            'message' => 'Registered successfully.',
            'token' => $token,
            'user' => $this->userPayload($user->load('company')),
        ], 'Registered successfully.', 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function user(Request $request): JsonResponse
    {
        return $this->respond([
            'user' => $this->userPayload($request->user()),
        ]);
    }

    private function userPayload(User $user): array
    {
        $user->loadMissing('company');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'company_id' => $user->company_id,
            'company' => $user->company ? [
                'id' => $user->company->id,
                'code' => $user->company->code,
                'name' => $user->company->name,
                'access_type' => $user->company->access_type,
            ] : null,
            'access' => [
                'command_center' => true,
                'erp_nexora' => $user->company?->access_type === 'internal',
            ],
        ];
    }
}
