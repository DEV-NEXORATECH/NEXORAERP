<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use BelongsToCompany, RecordsAudit;
    use SoftDeletes;

    protected $fillable = ['company_id',
        'name', 'bank_name', 'account_number', 'opening_balance'];
}
