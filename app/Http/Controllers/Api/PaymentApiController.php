<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Payment::class;
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
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
