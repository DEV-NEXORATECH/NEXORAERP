<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueSchedule extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'project_id', 'schedule_date', 'amount', 'status', 'notes',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
