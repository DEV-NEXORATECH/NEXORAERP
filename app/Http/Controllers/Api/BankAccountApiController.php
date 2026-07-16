<?php

namespace App\Http\Controllers\Api;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\BankAccount::class;
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
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
        ];
        return $rules;
    }
}
