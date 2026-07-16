<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySetting extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'company_name',
        'address',
        'email',
        'phone',
        'npwp',
        'logo_path',
        'signature_name',
        'default_bank_account_id',
    ];

    public function defaultBankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'default_bank_account_id');
    }
}
