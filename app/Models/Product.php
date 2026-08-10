<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id', 'category_id', 'unit_id', 'code', 'name', 'type', 'sku', 'barcode', 'cost', 'price', 'min_stock', 'description', 'is_active'];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'price' => 'decimal:2',
            'min_stock' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockOnHand(?int $warehouseId = null): float
    {
        $movements = $this->relationLoaded('movements')
            ? $this->movements
                ->when($warehouseId, fn ($c) => $c->where('warehouse_id', $warehouseId))
                ->where('is_posted', true)
            : $this->movements()
                ->when($warehouseId, fn ($q) => $q->where('warehouse_id', $warehouseId))
                ->where('is_posted', true)
                ->get();

        return (float) $movements->sum(fn (StockMovement $m) => $m->type === 'out' ? -$m->quantity : $m->quantity);
    }

    public function isLowStock(): bool
    {
        return $this->min_stock > 0 && $this->stockOnHand() < (float) $this->min_stock;
    }
}
