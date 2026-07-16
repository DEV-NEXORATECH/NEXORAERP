<?php

namespace App\Http\Controllers\Api;

use App\Models\ProcurementContract;
use Illuminate\Http\Request;

class ProcurementContractApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\ProcurementContract::class;
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
            'title' => 'required|string',
            'amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
