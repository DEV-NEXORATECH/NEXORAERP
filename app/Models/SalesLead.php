<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesLead extends Model
{
    protected $fillable = ['sales_inquiry_id', 'client_id', 'owner_id', 'title', 'stage', 'value', 'probability', 'expected_close_date', 'notes'];

    public function salesInquiry(): BelongsTo
    {
        return $this->belongsTo(SalesInquiry::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
