<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountingJournalApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\AccountingJournal::class;
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('search')}%")
                    ->orWhere('code', 'like', "%{$request->input('search')}%");
            });
        }

        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('accounting_journals', 'code')->ignore($id)],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['sales', 'purchase', 'bank', 'cash', 'general', 'opening', 'adjustment', 'asset'])],
            'default_chart_account_id' => ['nullable', 'exists:chart_accounts,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
