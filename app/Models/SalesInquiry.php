<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesInquiry extends Model
{
    protected $fillable = ['company_name', 'contact_name', 'email', 'phone', 'source', 'need', 'status', 'owner_id', 'notes'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
