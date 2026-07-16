<?php

namespace App\Http\Controllers\Api;

use App\Models\FixedAsset;
use Illuminate\Http\Request;

class FixedAssetApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\FixedAsset::class;
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
            'purchase_cost' => 'required|numeric',
        ];
        return $rules;
    }
}
