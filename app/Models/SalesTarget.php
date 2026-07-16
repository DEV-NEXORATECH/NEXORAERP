<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'user_id', 'period', 'target_amount', 'achieved_amount'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
