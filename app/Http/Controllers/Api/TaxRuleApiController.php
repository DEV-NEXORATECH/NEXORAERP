<?php

namespace App\Http\Controllers\Api;

use App\Models\TaxRule;
use Illuminate\Http\Request;

class TaxRuleApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\TaxRule::class;
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
            'name' => 'required|string',
            'tax_type' => 'required|string',
            'rate' => 'required|numeric',
        ];
        return $rules;
    }
}
