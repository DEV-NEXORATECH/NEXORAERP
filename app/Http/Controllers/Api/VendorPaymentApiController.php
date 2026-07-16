<?php

namespace App\Http\Controllers\Api;

use App\Models\VendorPayment;
use Illuminate\Http\Request;

class VendorPaymentApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\VendorPayment::class;
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
            'vendor_bill_id' => 'required|exists:vendor_bills,id',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
