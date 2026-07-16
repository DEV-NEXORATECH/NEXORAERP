<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequisition extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'requester_id', 'department_id', 'number', 'title', 'amount', 'required_date', 'status', 'reason'];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
