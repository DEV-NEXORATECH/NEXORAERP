<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Salary::class;
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
            'period' => 'required|string|max:7',
        ];
        return $rules;
    }
}
