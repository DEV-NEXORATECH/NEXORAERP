<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyVariance extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'rate_id', 'variance_percent', 'variance_amount', 'period', 'notes',
    ];

    public function rate(): BelongsTo
    {
        return $this->belongsTo(CurrencyRate::class, 'rate_id');
    }
}
