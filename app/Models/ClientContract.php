<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientContract extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'client_id', 'title', 'contract_number', 'start_date', 'end_date', 'reminder_date', 'amount', 'status', 'notes'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
