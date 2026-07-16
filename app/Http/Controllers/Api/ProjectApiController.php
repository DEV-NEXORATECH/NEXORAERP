<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Project::class;
    }

    protected function with(): array
    {
        return array (
  0 => 'clientRecord',
);
    }

    protected function filter(Request $request, $query)
    {
        
        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
            'contract_value' => 'nullable|numeric',
        ];
        return $rules;
    }
}
