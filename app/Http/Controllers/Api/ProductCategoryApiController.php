<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryApiController extends BaseApiController
{
    protected function model(): string
    {
        return ProductCategory::class;
    }

    protected function with(): array
    {
        return ['parent:id,code,name', 'products:id,category_id,code,name'];
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
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('product_categories', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
