<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesInquiry;
use Illuminate\Http\Request;

class SalesInquiryApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\SalesInquiry::class;
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
            'company_name' => 'required|string',
            'contact_name' => 'required|string',
        ];
        return $rules;
    }
}
