<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyVariance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rate_id', 'variance_percent', 'variance_amount', 'period', 'notes',
    ];

    public function rate(): BelongsTo
    {
        return $this->belongsTo(CurrencyRate::class, 'rate_id');
    }
}
