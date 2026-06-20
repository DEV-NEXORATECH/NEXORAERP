<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'schedule_date', 'amount', 'status', 'notes',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
