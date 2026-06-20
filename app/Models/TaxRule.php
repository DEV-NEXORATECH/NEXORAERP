<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{

    protected $fillable = ['name', 'tax_type', 'rate', 'direction', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
