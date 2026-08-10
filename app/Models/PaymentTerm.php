<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'code', 'name', 'due_days', 'due_type', 'is_active'];

    protected function casts(): array
    {
        return [
            'due_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
