<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueSchedule extends Model
{

    protected $fillable = [
        'project_id', 'schedule_date', 'amount', 'status', 'notes',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
