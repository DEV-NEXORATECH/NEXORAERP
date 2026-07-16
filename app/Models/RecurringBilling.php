<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringBilling extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'client_id', 'name', 'frequency', 'amount', 'tax_rate', 'next_invoice_date', 'end_date', 'status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
