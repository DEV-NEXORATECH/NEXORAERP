<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;

class Vendor extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'name', 'category', 'contact_name', 'email', 'phone', 'payment_terms', 'status'];
}
