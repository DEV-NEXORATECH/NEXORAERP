<?php

namespace App\Http\Controllers\Api;

use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitOfMeasureApiController extends BaseApiController
{
    protected function model(): string
    {
        return UnitOfMeasure::class;
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
            'code' => ['required', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', Rule::unique('units_of_measure', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'category' => ['required', Rule::in(['unit', 'weight', 'volume'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
