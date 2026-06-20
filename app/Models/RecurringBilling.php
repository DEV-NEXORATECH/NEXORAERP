<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringBilling extends Model
{
    use SoftDeletes;

    protected $fillable = ['client_id', 'name', 'frequency', 'amount', 'tax_rate', 'next_invoice_date', 'end_date', 'status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
