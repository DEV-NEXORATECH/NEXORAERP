<?php

namespace App\Http\Controllers\Api;

use App\Models\GoodsReceipt;
use Illuminate\Http\Request;

class GoodsReceiptApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\GoodsReceipt::class;
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
