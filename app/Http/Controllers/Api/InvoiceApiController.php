<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Invoice::class;
    }

    protected function with(): array
    {
        return array (
  0 => 'project',
  1 => 'payments',
);
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
        ];
        return $rules;
    }
}
