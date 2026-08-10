<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'code', 'name', 'symbol', 'rate', 'is_active'];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:6',
            'is_active' => 'boolean',
        ];
    }
}
