<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollBenefit extends Model
{
    use SoftDeletes;

    protected $fillable = ['salary_id', 'employee_id', 'period', 'bpjs_health', 'bpjs_employment', 'pph21', 'incentive', 'status'];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
