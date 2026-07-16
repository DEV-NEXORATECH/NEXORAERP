<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseApiController extends Controller
{
    use ApiResponse;

    abstract protected function model(): string;

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
        return [];
    }

    protected function findRecord(mixed $id): Model
    {
        if ($id instanceof Model) {
            return $id;
        }

        return $this->model()::with($this->with())->findOrFail($id);
    }

    protected function findRecordForUpdate(mixed $id): Model
    {
        if ($id instanceof Model) {
            return $id;
        }

        return $this->model()::findOrFail($id);
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->model()::with($this->with());
        $query = $this->applyCompanyFilter($request, $query);
        $query = $this->filter($request, $query);
        $perPage = min((int) $request->get('per_page', 15), 100);

        return $this->respond($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->validationRules());
        $model = $this->model();

        $targetCompanyId = $this->resolveTargetCompanyId($request, $data, $model);
        $this->guardCompanyForeignKeys($data, $targetCompanyId);

        if ($targetCompanyId && $this->modelHasCompany($model)) {
            $data['company_id'] = $targetCompanyId;
        }

        $record = $this->model()::create($data);
        if (method_exists($this, 'afterStore')) {
            $this->afterStore($record, $request);
        }

        return $this->respond($record->load($this->with()), 'Created successfully.', 201);
    }

    public function show(mixed $id): JsonResponse
    {
        return $this->respond($this->findRecord($id));
    }

    public function update(Request $request, mixed $id): JsonResponse
    {
        $record = $this->findRecordForUpdate($id);
        $data = $request->validate($this->validationRules(true, $record->getKey()));
        $targetCompanyId = $this->resolveTargetCompanyId($request, $data, $this->model(), $record);
        $this->guardCompanyForeignKeys($data, $targetCompanyId);

        if ($targetCompanyId && in_array('company_id', $record->getFillable(), true)) {
            $data['company_id'] = $targetCompanyId;
        }

        $record->update($data);

        return $this->respond($record->fresh()->load($this->with()), 'Updated successfully.');
    }

    public function destroy(mixed $id): JsonResponse
    {
        $record = $this->findRecordForUpdate($id);
        $record->delete();

        return $this->respondDeleted();
    }

    private function applyCompanyFilter(Request $request, $query)
    {
        $model = $this->model();
        if (!$this->modelHasCompany($model)) {
            return $query;
        }

        $user = $request->user()?->loadMissing('company');
        if (!$user?->company_id || $user->company?->access_type !== 'internal') {
            return $query;
        }

        $companyId = $this->companyIdFromRequest($request);
        if ($companyId) {
            $query->where((new $model)->getTable() . '.company_id', $companyId);
        }

        return $query;
    }

    private function resolveTargetCompanyId(Request $request, array $data, string $model, ?Model $record = null): ?int
    {
        if (!$this->modelHasCompany($model)) {
            return null;
        }

        $user = $request->user()?->loadMissing('company');
        if (!$user?->company_id) {
            return null;
        }

        if ($user->company?->access_type !== 'internal') {
            return (int) $user->company_id;
        }

        $companyId = $this->companyIdFromRequest($request)
            ?? $this->inferCompanyIdFromForeignKeys($data)
            ?? $record?->company_id
            ?? $user->company_id;

        if (!Company::query()->whereKey($companyId)->where('is_active', true)->exists()) {
            throw ValidationException::withMessages([
                'company_id' => ['The selected company is invalid or inactive.'],
            ]);
        }

        return (int) $companyId;
    }

    private function companyIdFromRequest(Request $request): ?int
    {
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

    private function inferCompanyIdFromForeignKeys(array $data): ?int
    {
        foreach ($this->companyForeignKeyMap() as $field => $model) {
            if (empty($data[$field])) {
                continue;
            }

            $record = $model::query()->whereKey($data[$field])->first();
            if ($record && in_array('company_id', $record->getFillable(), true)) {
                return (int) $record->company_id;
            }
        }

        return null;
    }

    private function guardCompanyForeignKeys(array $data, ?int $targetCompanyId): void
    {
        if (!$targetCompanyId) {
            return;
        }

        foreach ($this->companyForeignKeyMap() as $field => $model) {
            if (empty($data[$field])) {
                continue;
            }

            $query = $model::query()->whereKey($data[$field]);
            if (in_array('company_id', (new $model)->getFillable(), true)) {
                $query->where('company_id', $targetCompanyId);
            }

            if (!$query->exists()) {
                throw ValidationException::withMessages([
                    $field => ['The selected record does not belong to the target company.'],
                ]);
            }
        }
    }

    private function modelHasCompany(string $model): bool
    {
        return in_array('company_id', (new $model)->getFillable(), true);
    }

    private function companyForeignKeyMap(): array
    {
        return [
            'assigned_to' => \App\Models\User::class,
            'bank_account_id' => \App\Models\BankAccount::class,
            'budget_id' => \App\Models\Budget::class,
            'cashflow_id' => \App\Models\Cashflow::class,
            'chart_account_id' => \App\Models\ChartAccount::class,
            'client_contract_id' => \App\Models\ClientContract::class,
            'client_id' => \App\Models\Client::class,
            'created_by' => \App\Models\User::class,
            'department_id' => \App\Models\Department::class,
            'employee_id' => \App\Models\Employee::class,
            'expense_category_id' => \App\Models\ExpenseCategory::class,
            'invoice_id' => \App\Models\Invoice::class,
            'job_position_id' => \App\Models\JobPosition::class,
            'owner_id' => \App\Models\User::class,
            'project_id' => \App\Models\Project::class,
            'proposal_id' => \App\Models\Proposal::class,
            'purchase_order_id' => \App\Models\PurchaseOrder::class,
            'purchase_requisition_id' => \App\Models\PurchaseRequisition::class,
            'requester_id' => \App\Models\User::class,
            'reviewer_id' => \App\Models\User::class,
            'sales_inquiry_id' => \App\Models\SalesInquiry::class,
            'sales_lead_id' => \App\Models\SalesLead::class,
            'task_id' => \App\Models\Task::class,
            'user_id' => \App\Models\User::class,
            'vendor_bill_id' => \App\Models\VendorBill::class,
            'vendor_id' => \App\Models\Vendor::class,
        ];
    }
}
