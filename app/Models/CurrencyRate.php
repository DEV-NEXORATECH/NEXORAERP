<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'from_currency', 'to_currency', 'rate', 'rate_date', 'source',
    ];
}
