<?php

namespace App\Http\Traits;

use App\Models\AuditLog;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
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
            'company_id'     => $model->company_id ?? auth()->user()?->company_id,
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

    protected function companyContextId(Request $request): ?int
    {
        $user = $request->user()?->loadMissing('company');
        if (!$user?->company_id || $user->company?->access_type !== 'internal') {
            return null;
        }

        if ($request->filled('company_id')) {
            return (int) $request->input('company_id');
        }

        if (!$request->filled('company_code')) {
            return null;
        }

        $company = Company::query()
            ->whereRaw('UPPER(code) = ?', [strtoupper(trim((string) $request->input('company_code')))])
            ->where('is_active', true)
            ->first();

        if (!$company) {
            throw ValidationException::withMessages([
                'company_code' => ['Company code is invalid or inactive.'],
            ]);
        }

        return (int) $company->id;
    }

    protected function applyCompanyContext(Request $request, $query)
    {
        $companyId = $this->companyContextId($request);
        $model = $query->getModel();

        if ($companyId && in_array('company_id', $model->getFillable(), true)) {
            $query->where($model->getTable() . '.company_id', $companyId);
        }

        return $query;
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
