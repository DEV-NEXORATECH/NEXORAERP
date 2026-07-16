<?php

namespace App\Http\Controllers\Api;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Timesheet::class;
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
            'hours' => 'required|numeric|min:0|max:24',
        ];
        return $rules;
    }
}
