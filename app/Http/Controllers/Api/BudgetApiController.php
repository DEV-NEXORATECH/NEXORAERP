<?php

namespace App\Http\Controllers\Api;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Budget::class;
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
            'period' => 'required|string',
            'budget_amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
