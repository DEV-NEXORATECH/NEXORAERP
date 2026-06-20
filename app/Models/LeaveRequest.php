<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{

    protected $fillable = ['employee_id', 'type', 'start_date', 'end_date', 'status', 'reason'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
