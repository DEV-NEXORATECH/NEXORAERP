<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait SyncsOrderItems
{
    protected function syncOrderItems(Model $order, string $itemClass, ?array $items = null): float
    {
        $items = $items ?? [];

        $order->items()->delete();

        $total = 0.0;
        foreach (array_values($items) as $row) {
            $productId = (int) Arr::get($row, 'product_id');
            $quantity = (float) Arr::get($row, 'quantity', 0);
            $unitPrice = (float) Arr::get($row, 'unit_price', 0);

            if (!$productId || $quantity <= 0) {
                continue;
            }

            $amount = round($quantity * $unitPrice, 2);
            $total += $amount;

            $itemClass::create([
                'company_id' => $order->company_id,
                $order instanceof \App\Models\SalesOrder ? 'sales_order_id' : 'purchase_order_id' => $order->getKey(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'amount' => $amount,
                'notes' => Arr::get($row, 'notes'),
            ]);
        }

        return $total;
    }

    protected function postOrderStockMovements(Model $order, string $type, string $sourceType, string $reference): void
    {
        $order->load('items');

        foreach ($order->items as $item) {
            $exists = \App\Models\StockMovement::where('source_type', $sourceType)
                ->where('source_id', $order->getKey())
                ->where('product_id', $item->product_id)
                ->exists();

            if ($exists) {
                continue;
            }

            \App\Models\StockMovement::create([
                'company_id' => $order->company_id,
                'product_id' => $item->product_id,
                'warehouse_id' => null,
                'location_id' => null,
                'type' => $type,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'reference' => $reference,
                'source_type' => $sourceType,
                'source_id' => $order->getKey(),
                'movement_date' => $order->order_date,
                'is_posted' => true,
                'notes' => $order->notes,
            ]);
        }
    }
}
