<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;

class TaxRule extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'name', 'tax_type', 'rate', 'direction', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
