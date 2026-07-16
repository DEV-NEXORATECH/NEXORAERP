<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliationItem extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'bank_account_id', 'statement_date', 'description', 'reference',
        'amount', 'type', 'reconciled', 'reconciled_at',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
