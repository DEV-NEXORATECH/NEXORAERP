<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientContract extends Model
{
    protected $fillable = ['client_id', 'title', 'contract_number', 'start_date', 'end_date', 'reminder_date', 'amount', 'status', 'notes'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
