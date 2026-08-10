<?php

namespace App\Http\Controllers\Api;

use App\Models\StockLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockLocationApiController extends BaseApiController
{
    protected function model(): string
    {
        return StockLocation::class;
    }

    protected function with(): array
    {
        return ['warehouse:id,code,name'];
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('search')}%")
                    ->orWhere('code', 'like', "%{$request->input('search')}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/'],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['internal', 'input', 'output', 'transit'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
