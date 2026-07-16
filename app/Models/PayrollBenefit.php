<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollBenefit extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'salary_id', 'employee_id', 'period', 'bpjs_health', 'bpjs_employment', 'pph21', 'incentive', 'status'];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
