<?php

namespace App\Http\Controllers\Api;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\Proposal::class;
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
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'amount' => 'nullable|numeric',
        ];
        return $rules;
    }
}
