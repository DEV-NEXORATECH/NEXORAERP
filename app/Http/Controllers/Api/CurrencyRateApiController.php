<?php

namespace App\Http\Controllers\Api;

use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\CurrencyRate::class;
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
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'rate' => 'required|numeric',
        ];
        return $rules;
    }
}
