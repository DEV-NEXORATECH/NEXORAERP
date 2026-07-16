<?php

namespace App\Http\Controllers\Api;

use App\Models\BankReconciliationItem;
use Illuminate\Http\Request;

class BankReconciliationItemApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\BankReconciliationItem::class;
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
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
