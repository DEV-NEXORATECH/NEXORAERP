<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Task::class;
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
            'title' => 'required|string|max:255',
        ];
        return $rules;
    }
}
