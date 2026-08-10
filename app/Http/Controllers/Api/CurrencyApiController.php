<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrencyApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Currency::class;
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
            'code' => ['required', 'max:8', 'regex:/^[A-Za-z]{2,8}$/', Rule::unique('currencies', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'symbol' => ['nullable', 'max:8'],
            'rate' => ['required', 'numeric', 'min:0.000001'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
