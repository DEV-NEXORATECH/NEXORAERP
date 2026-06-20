<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{

    protected $fillable = [
        'from_currency', 'to_currency', 'rate', 'rate_date', 'source',
    ];
}
