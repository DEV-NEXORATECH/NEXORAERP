<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementContract extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'vendor_id', 'title', 'contract_number', 'start_date', 'end_date', 'renewal_reminder_date', 'amount', 'status'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
