<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\SalesOrder::class;
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
            'client_id' => 'required|exists:clients,id',
            'amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
