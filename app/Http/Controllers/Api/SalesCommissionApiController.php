<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesCommission;
use Illuminate\Http\Request;

class SalesCommissionApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\SalesCommission::class;
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
            'base_amount' => 'required|numeric',
        ];
        return $rules;
    }
}
