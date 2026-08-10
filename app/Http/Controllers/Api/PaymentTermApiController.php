<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentTermApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PaymentTerm::class;
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('search')}%")
                    ->orWhere('code', 'like', "%{$request->input('search')}%");
            });
        }

        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('payment_terms', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'due_days' => ['required', 'integer', 'min:0', 'max:3660'],
            'due_type' => ['required', Rule::in(['day', 'month'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
