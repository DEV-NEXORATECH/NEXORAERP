<?php

namespace App\Http\Controllers\Api;

use App\Models\EmployeeSkill;
use Illuminate\Http\Request;

class EmployeeSkillApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\EmployeeSkill::class;
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
            'skill' => 'required|string',
        ];
        return $rules;
    }
}
