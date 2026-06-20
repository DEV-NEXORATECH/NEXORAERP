<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSkill extends Model
{
    use SoftDeletes;

    protected $fillable = ['employee_id', 'skill', 'level', 'notes'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
