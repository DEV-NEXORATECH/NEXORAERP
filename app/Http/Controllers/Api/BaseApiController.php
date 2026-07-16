<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
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
        $query = $this->filter($request, $query);
        $perPage = min((int) $request->get('per_page', 15), 100);
        return $this->respond($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->validationRules());
        $model = $this->model();

        $this->guardCompanyForeignKeys($request, $data);

        if ($request->user()?->company_id && in_array('company_id', (new $model)->getFillable(), true)) {
            $user = $request->user()->loadMissing('company');
            if ($user->company?->access_type === 'internal') {
                $data['company_id'] = $data['company_id'] ?? $user->company_id;
            } else {
                $data['company_id'] = $user->company_id;
            }
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
        $this->guardCompanyForeignKeys($request, $data);
        $record->update($data);
        return $this->respond($record->fresh()->load($this->with()), 'Updated successfully.');
    }

    public function destroy(mixed $id): JsonResponse
    {
        $record = $this->findRecordForUpdate($id);
        $record->delete();
        return $this->respondDeleted();
    }

    private function guardCompanyForeignKeys(Request $request, array $data): void
    {
        $companyId = $request->user()?->company_id;
        $user = $request->user()?->loadMissing('company');
        if (!$companyId || $user?->company?->access_type === 'internal') {
            return;
        }

        $relations = [
            'bank_account_id' => \App\Models\BankAccount::class,
            'cashflow_id' => \App\Models\Cashflow::class,
            'client_id' => \App\Models\Client::class,
            'department_id' => \App\Models\Department::class,
            'employee_id' => \App\Models\Employee::class,
            'invoice_id' => \App\Models\Invoice::class,
            'job_position_id' => \App\Models\JobPosition::class,
            'project_id' => \App\Models\Project::class,
            'proposal_id' => \App\Models\Proposal::class,
            'sales_inquiry_id' => \App\Models\SalesInquiry::class,
            'vendor_id' => \App\Models\Vendor::class,
            'vendor_bill_id' => \App\Models\VendorBill::class,
            'purchase_requisition_id' => \App\Models\PurchaseRequisition::class,
            'purchase_order_id' => \App\Models\PurchaseOrder::class,
            'created_by' => \App\Models\User::class,
            'assigned_to' => \App\Models\User::class,
            'owner_id' => \App\Models\User::class,
            'reviewer_id' => \App\Models\User::class,
            'requester_id' => \App\Models\User::class,
            'user_id' => \App\Models\User::class,
        ];

        foreach ($relations as $field => $model) {
            if (empty($data[$field])) {
                continue;
            }

            $query = $model::query()->whereKey($data[$field]);
            if (in_array('company_id', (new $model)->getFillable(), true)) {
                $query->where('company_id', $companyId);
            }

            if (!$query->exists()) {
                throw ValidationException::withMessages([
                    $field => ['The selected record does not belong to your company.'],
                ]);
            }
        }
    }
}
