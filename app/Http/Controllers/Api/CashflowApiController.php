<?php

namespace App\Http\Controllers\Api;

use App\Models\Cashflow;
use Illuminate\Http\Request;

class CashflowApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Cashflow::class;
    }

    protected function with(): array
    {
        return array (
  0 => 'project',
  1 => 'expenseCategory',
  2 => 'bankAccount',
);
    }

    protected function filter(Request $request, $query)
    {
        
        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
        ];
        return $rules;
    }
}
