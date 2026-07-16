<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\User::class;
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
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,hr,finance,sales',
        ];
        if ($isUpdate && $id) {
            $rules = array_merge($rules, [
                // unique rules with ignore handled in child if needed
            ]);
        }
        return $rules;
    }
}
