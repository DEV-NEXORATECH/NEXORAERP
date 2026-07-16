<?php

namespace App\Http\Controllers\Api;

use App\Models\Reimbursement;
use Illuminate\Http\Request;

class ReimbursementApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Reimbursement::class;
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
            'employee_id' => 'required|exists:employees,id',
            'description' => 'required|string',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
