<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;

class CurrencyRate extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'from_currency', 'to_currency', 'rate', 'rate_date', 'source',
    ];
}
