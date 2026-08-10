<?php

namespace App\Http\Controllers\Api;

use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockMovementApiController extends BaseApiController
{
    protected function model(): string
    {
        return StockMovement::class;
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

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date('date_to'));
        }

        return $query->orderByDesc('movement_date');
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'location_id' => ['nullable', 'exists:stock_locations,id'],
            'type' => ['required', Rule::in(['in', 'out', 'adjustment'])],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'reference' => ['nullable', 'max:100'],
            'movement_date' => ['required', 'date'],
            'is_posted' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function stock(Request $request): \Illuminate\Http\JsonResponse
    {
        $productId = $request->integer('product_id') ?: null;

        $totals = \App\Models\Product::query()
            ->when($productId, fn ($q) => $q->whereKey($productId))
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'stock_on_hand' => $product->stockOnHand(),
                'low_stock' => $product->isLowStock(),
            ]);

        return $this->respond($totals);
    }
}
