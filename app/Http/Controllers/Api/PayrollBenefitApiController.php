<?php

namespace App\Http\Controllers\Api;

use App\Models\PayrollBenefit;
use Illuminate\Http\Request;

class PayrollBenefitApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PayrollBenefit::class;
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
            'salary_id' => 'required|exists:salaries,id',
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string',
        ];
        return $rules;
    }
}
