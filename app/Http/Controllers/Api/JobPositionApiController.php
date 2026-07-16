<?php

namespace App\Http\Controllers\Api;

use App\Models\JobPosition;
use Illuminate\Http\Request;

class JobPositionApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\JobPosition::class;
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
            'name' => 'required|string|max:255|unique:job_positions,name',
        ];
        if ($isUpdate && $id) {
            $rules = array_merge($rules, [
                // unique rules with ignore handled in child if needed
            ]);
        }
        return $rules;
    }
}
