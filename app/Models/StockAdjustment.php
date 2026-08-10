<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id', 'product_id', 'warehouse_id', 'location_id', 'number', 'expected_quantity', 'actual_quantity', 'reason', 'adjustment_date', 'status', 'notes'];

    protected function casts(): array
    {
        return [
            'expected_quantity' => 'decimal:4',
            'actual_quantity' => 'decimal:4',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StockLocation::class, 'location_id');
    }

    public function deltaQuantity(): float
    {
        return round((float) $this->actual_quantity - (float) $this->expected_quantity, 4);
    }
}
