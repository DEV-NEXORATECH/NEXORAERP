<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait AppliesListFilters
{
    protected function applyListFilters(Builder $query, Request $request, array $searchColumns = []): Builder
    {
        $model = $query->getModel();
        $table = $model->getTable();
        $columns = Schema::getColumnListing($table);
        $has = fn (string $column) => in_array($column, $columns, true);

        if ($request->filled('search') && $searchColumns) {
            $search = $request->string('search')->toString();
            $query->where(function (Builder $q) use ($searchColumns, $search, $has) {
                foreach ($searchColumns as $column) {
                    if ($has($column)) {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                }
            });
        }

        foreach (['status', 'type', 'tax_type', 'asset_type', 'currency'] as $column) {
            if ($request->filled($column) && $has($column)) {
                $query->where($column, $request->input($column));
            }
        }

        if ($request->filled('type') && $has('role')) {
            $query->where('role', $request->input('type'));
        }

        foreach (['project_id', 'bank_account_id', 'chart_account_id', 'employee_id', 'client_id', 'department_id', 'assigned_to', 'user_id', 'vendor_id'] as $column) {
            if ($request->filled($column) && $has($column)) {
                $query->where($column, $request->input($column));
            }
        }

        $dateColumn = collect(['transaction_date', 'issue_date', 'due_date', 'bill_date', 'payment_date', 'entry_date', 'rate_date', 'start_date', 'end_date', 'date', 'created_at'])
            ->first(fn ($column) => $has($column));

        if ($dateColumn) {
            if ($request->filled('date_from')) {
                $query->whereDate($dateColumn, '>=', $request->date('date_from')->toDateString());
            }

            if ($request->filled('date_to')) {
                $query->whereDate($dateColumn, '<=', $request->date('date_to')->toDateString());
            }
        }

        return $query;
    }
}
