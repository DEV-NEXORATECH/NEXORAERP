<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseMatch extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'purchase_order_id', 'goods_receipt_id', 'variance_amount',
        'match_status', 'matched_at', 'notes',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }
}
