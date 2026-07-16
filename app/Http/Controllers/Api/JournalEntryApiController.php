<?php

namespace App\Http\Controllers\Api;

use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryApiController extends BaseApiController
{
    protected function model(): string
    {
        return \App\Models\JournalEntry::class;
    }

    protected function with(): array
    {
        return array (
  0 => 'lines',
);
    }

    protected function filter(Request $request, $query)
    {
        
        return $query;
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        $rules = [
            'entry_date' => 'required|date',
            'memo' => 'nullable|string',
        ];
        return $rules;
    }
}
