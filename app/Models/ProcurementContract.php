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

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_reminder_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
