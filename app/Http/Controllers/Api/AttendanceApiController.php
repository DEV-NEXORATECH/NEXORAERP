<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Attendance::class;
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
            'work_date' => 'required|date',
        ];
        return $rules;
    }
}
