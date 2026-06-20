<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyVariance extends Model
{

    protected $fillable = [
        'rate_id', 'variance_percent', 'variance_amount', 'period', 'notes',
    ];

    public function rate(): BelongsTo
    {
        return $this->belongsTo(CurrencyRate::class, 'rate_id');
    }
}
