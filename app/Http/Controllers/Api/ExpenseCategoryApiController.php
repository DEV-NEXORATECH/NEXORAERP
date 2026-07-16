<?php

namespace App\Http\Controllers\Api;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\ExpenseCategory::class;
    }

    protected function with(): array
    {
        return [];
    }

    protected function filter(Request $request, $query)
    {
        
        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
        ];
        return $rules;
    }
}
