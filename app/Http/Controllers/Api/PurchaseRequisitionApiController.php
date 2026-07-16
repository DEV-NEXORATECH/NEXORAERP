<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;

class PurchaseRequisitionApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PurchaseRequisition::class;
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
            'title' => 'required|string',
            'amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
