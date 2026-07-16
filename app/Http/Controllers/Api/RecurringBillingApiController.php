<?php

namespace App\Http\Controllers\Api;

use App\Models\RecurringBilling;
use Illuminate\Http\Request;

class RecurringBillingApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\RecurringBilling::class;
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
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'frequency' => 'required|string',
        ];
        return $rules;
    }
}
