<?php

namespace App\Http\Controllers\Api;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseApiController extends BaseApiController
{
    protected function model(): string
    {
        return Warehouse::class;
    }

    protected function with(): array
    {
        return ['locations:id,warehouse_id,code,name'];
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
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('warehouses', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
