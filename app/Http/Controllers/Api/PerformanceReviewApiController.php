<?php

namespace App\Http\Controllers\Api;

use App\Models\PerformanceReview;
use Illuminate\Http\Request;

class PerformanceReviewApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\PerformanceReview::class;
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
            'period' => 'required|string',
        ];
        return $rules;
    }
}
