<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\SyncsOrderItems;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurchaseOrderApiController extends BaseApiController
{
    use SyncsOrderItems;

    protected function model(): string
    {
        return \App\Models\PurchaseOrder::class;
    }

    protected function with(): array
    {
        return ['items.product:id,code,name', 'vendor:id,name'];
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->input('vendor_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date('date_to'));
        }

        return $query->latest('order_date');
    }

    protected function validationRules(bool $isUpdate = false, mixed $id = null): array
    {
        return [
            'vendor_id'               => ['nullable', 'exists:vendors,id'],
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'order_date'              => ['required', 'date'],
            'amount'                  => ['nullable', 'numeric', 'min:0'],
            'status'                  => ['sometimes', Rule::in(['ordered', 'partial_received', 'received', 'cancelled'])],
            'notes'                   => ['nullable', 'string'],
            'items'                   => ['nullable', 'array'],
            'items.*.product_id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'        => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price'      => ['nullable', 'numeric', 'min:0'],
            'items.*.notes'           => ['nullable', 'string'],
        ];
    }

    protected function prepareStoreData(Request $request, array $data): array
    {
        $data['number'] ??= 'PO-' . now()->format('Ym') . '-' . str_pad((string) (PurchaseOrder::count() + 1), 4, '0', STR_PAD_LEFT);
        $data['status'] ??= 'ordered';
        $data['amount'] = 0;
        unset($data['items']);

        return $data;
    }

    protected function afterStore($record, Request $request): void
    {
        $total = $this->syncOrderItems($record, PurchaseOrderItem::class, $request->input('items'));
        if ($total) {
            $record->update(['amount' => $total]);
        }

        if ($record->status === 'received') {
            $this->postOrderStockMovements($record, 'in', 'purchase_order', $record->number);
        }
    }

    protected function prepareUpdateData(Request $request, array $data, $record): array
    {
        unset($data['items']);
        if (array_key_exists('amount', $data)) {
            unset($data['amount']);
        }

        return $data;
    }

    protected function afterUpdate($record, Request $request): void
    {
        $total = $this->syncOrderItems($record, PurchaseOrderItem::class, $request->input('items'));
        if ($total) {
            $record->update(['amount' => $total]);
        }

        if ($record->status === 'received') {
            $this->postOrderStockMovements($record, 'in', 'purchase_order', $record->number);
        }
    }
}
