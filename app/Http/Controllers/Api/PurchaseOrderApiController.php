<?php

namespace App\Http\Controllers\Api;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PurchaseOrder::class;
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
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
