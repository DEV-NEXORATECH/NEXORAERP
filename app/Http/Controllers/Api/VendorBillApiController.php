<?php

namespace App\Http\Controllers\Api;

use App\Models\VendorBill;
use Illuminate\Http\Request;

class VendorBillApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\VendorBill::class;
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
            'vendor_name' => 'required|string',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
