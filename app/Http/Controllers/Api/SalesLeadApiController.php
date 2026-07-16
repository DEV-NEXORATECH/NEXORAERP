<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesLead;
use Illuminate\Http\Request;

class SalesLeadApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\SalesLead::class;
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
            'title' => 'required|string',
        ];
        return $rules;
    }
}
