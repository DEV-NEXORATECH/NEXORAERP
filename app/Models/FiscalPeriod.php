<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;

class FiscalPeriod extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'code', 'name', 'start_date', 'end_date', 'status'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
