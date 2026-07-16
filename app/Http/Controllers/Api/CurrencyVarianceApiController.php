<?php

namespace App\Http\Controllers\Api;

use App\Models\CurrencyVariance;
use Illuminate\Http\Request;

class CurrencyVarianceApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\CurrencyVariance::class;
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
            'rate_id' => 'required|exists:currency_rates,id',
            'period' => 'required|string',
        ];
        return $rules;
    }
}
