<?php

namespace App\Http\Controllers\Api;

use App\Models\ChartAccount;
use Illuminate\Http\Request;

class ChartAccountApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\ChartAccount::class;
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
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ];
        return $rules;
    }
}
