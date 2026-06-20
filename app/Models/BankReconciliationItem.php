<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliationItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_account_id', 'statement_date', 'description', 'reference',
        'amount', 'type', 'reconciled', 'reconciled_at',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
