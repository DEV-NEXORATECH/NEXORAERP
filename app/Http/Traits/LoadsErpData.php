<?php

namespace App\Http\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait LoadsErpData
{
    protected function cashflowSummary(Collection $cashflows): array
    {
        $income  = $cashflows->where('type', 'income')->sum('amount');
        $expense = $cashflows->where('type', 'expense')->sum('amount');

        return ['income' => $income, 'expense' => $expense, 'balance' => $income - $expense];
    }

    protected function audit(string $action, Model $model, string $description, ?array $old = null, ?array $new = null): void
    {
        AuditLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'auditable_type' => class_basename($model),
            'auditable_id'   => $model->getKey(),
            'description'    => $description,
            'changes'        => ['old' => $old, 'new' => $new],
        ]);
    }

    protected function nextNumber(string $prefix, int $sequence): string
    {
        return $prefix . '-' . now()->format('Ym') . '-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    protected function storeUpload(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($folder, 'public');
    }

    protected function masterModels(): array
    {
        return [
            'clients'            => \App\Models\Client::class,
            'departments'        => \App\Models\Department::class,
            'job_positions'      => \App\Models\JobPosition::class,
            'expense_categories' => \App\Models\ExpenseCategory::class,
            'bank_accounts'      => \App\Models\BankAccount::class,
        ];
    }

    protected function csv(string $filename, array $header, Collection $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $header);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
