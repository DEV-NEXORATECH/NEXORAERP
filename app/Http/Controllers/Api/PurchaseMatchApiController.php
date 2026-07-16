<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseMatch;
use Illuminate\Http\Request;

class PurchaseMatchApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PurchaseMatch::class;
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
            'purchase_order_id' => 'required|exists:purchase_orders,id',
        ];
        return $rules;
    }
}
