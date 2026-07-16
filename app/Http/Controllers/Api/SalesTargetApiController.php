<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesTarget;
use Illuminate\Http\Request;

class SalesTargetApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\SalesTarget::class;
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
            'user_id' => 'required|exists:users,id',
            'period' => 'required|string',
            'target_amount' => 'required|numeric',
        ];
        return $rules;
    }
}
