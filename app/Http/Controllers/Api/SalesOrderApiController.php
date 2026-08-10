<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\SyncsOrderItems;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesOrderApiController extends BaseApiController
{
    use SyncsOrderItems;

    protected function model(): string
    {
        return \App\Models\SalesOrder::class;
    }

    protected function with(): array
    {
        return ['items.product:id,code,name', 'client:id,name'];
    }

    protected function filter(Request $request, $query)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
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
            'client_id'   => ['nullable', 'exists:clients,id'],
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'title'       => ['nullable', 'max:255'],
            'amount'      => ['nullable', 'numeric', 'min:0'],
            'order_date'  => ['required', 'date'],
            'status'      => ['sometimes', Rule::in(['draft', 'confirmed', 'delivered', 'cancelled'])],
            'notes'       => ['nullable', 'string'],
            'items'       => ['nullable', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.notes'      => ['nullable', 'string'],
        ];
    }

    protected function prepareStoreData(Request $request, array $data): array
    {
        $data['number'] ??= 'SO-' . now()->format('Ym') . '-' . str_pad((string) (SalesOrder::count() + 1), 4, '0', STR_PAD_LEFT);
        $data['status'] ??= 'draft';
        $data['amount'] = 0;
        unset($data['items']);

        return $data;
    }

    protected function afterStore($record, Request $request): void
    {
        $total = $this->syncOrderItems($record, SalesOrderItem::class, $request->input('items'));
        if ($total) {
            $record->update(['amount' => $total]);
        }

        if ($record->status === 'confirmed') {
            $this->postOrderStockMovements($record, 'out', 'sales_order', $record->number);
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
        $total = $this->syncOrderItems($record, SalesOrderItem::class, $request->input('items'));
        if ($total) {
            $record->update(['amount' => $total]);
        }

        if ($record->status === 'confirmed') {
            $this->postOrderStockMovements($record, 'out', 'sales_order', $record->number);
        }
    }
}
