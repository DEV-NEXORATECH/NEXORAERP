<?php

namespace App\Http\Controllers\Api;

use App\Models\RevenueSchedule;
use Illuminate\Http\Request;

class RevenueScheduleApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\RevenueSchedule::class;
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
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric',
            'schedule_date' => 'required|date',
        ];
        return $rules;
    }
}
