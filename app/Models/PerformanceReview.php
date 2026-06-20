<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceReview extends Model
{
    use SoftDeletes;

    protected $fillable = ['employee_id', 'period', 'kpi_score', 'okr_score', 'rating', 'notes'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
