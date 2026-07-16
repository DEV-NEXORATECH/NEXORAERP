<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('company');

        $query = Company::query()->orderBy('name');

        if ($user->company?->access_type !== 'internal') {
            $query->whereKey($user->company_id);
        }

        return response()->json([
            'message' => 'OK',
            'data' => $query->get(['id', 'code', 'access_type', 'name', 'email', 'phone', 'address', 'tax_id', 'is_active']),
        ]);
    }
}
