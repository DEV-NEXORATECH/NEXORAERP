<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Client::class;
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
            'email' => 'nullable|email',
        ];
        return $rules;
    }
}
