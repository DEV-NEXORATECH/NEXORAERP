<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = ['employee_id', 'work_date', 'check_in', 'check_out', 'work_mode', 'status', 'notes'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
