<?php

namespace App\Http\Controllers\Api;

use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockAdjustmentApiController extends BaseApiController
{
    protected function model(): string
    {
        return StockAdjustment::class;
    }

    protected function with(): array
    {
        return ['product:id,code,name', 'warehouse:id,code,name', 'location:id,code,name'];
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return $query->latest('adjustment_date');
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'location_id' => ['nullable', 'exists:stock_locations,id'],
            'expected_quantity' => ['nullable', 'numeric', 'min:0'],
            'actual_quantity' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'max:100'],
            'adjustment_date' => ['required', 'date'],
            'status' => ['sometimes', Rule::in(['draft', 'posted'])],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareStoreData(Request $request, array $data): array
    {
        $data['number'] ??= 'ADJ-' . now()->format('Ym') . '-' . str_pad((string) (StockAdjustment::count() + 1), 4, '0', STR_PAD_LEFT);
        $data['status'] = 'draft';

        return $data;
    }

    protected function afterStore($record, Request $request): void
    {
        $record->update(['expected_quantity' => $record->product?->stockOnHand($record->warehouse_id ?: null) ?? 0]);
    }

    public function post(Request $request, StockAdjustment $adjustment): JsonResponse
    {
        if ($adjustment->status === 'posted') {
            return $this->respondError('Adjustment sudah diposting.', 409);
        }

        $delta = $adjustment->deltaQuantity();

        if ($delta != 0) {
            StockMovement::create([
                'company_id' => $adjustment->company_id,
                'product_id' => $adjustment->product_id,
                'warehouse_id' => $adjustment->warehouse_id,
                'location_id' => $adjustment->location_id,
                'type' => $delta > 0 ? 'in' : 'out',
                'quantity' => abs($delta),
                'unit_price' => $adjustment->product?->cost ?? 0,
                'reference' => $adjustment->number,
                'source_type' => 'adjustment',
                'source_id' => $adjustment->id,
                'movement_date' => $adjustment->adjustment_date,
                'is_posted' => true,
                'notes' => $adjustment->reason,
            ]);
        }

        $adjustment->update(['status' => 'posted']);

        return $this->respond($adjustment->fresh()->load($this->with()), 'Adjustment stok diposting.');
    }
}
