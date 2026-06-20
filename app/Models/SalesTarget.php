<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    protected $fillable = ['user_id', 'period', 'target_amount', 'achieved_amount'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
