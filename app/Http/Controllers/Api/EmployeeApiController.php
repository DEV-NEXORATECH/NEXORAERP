<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Employee::class;
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
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ];
        return $rules;
    }
}
