<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientContract;
use Illuminate\Http\Request;

class ClientContractApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\ClientContract::class;
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
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string',
        ];
        return $rules;
    }
}
