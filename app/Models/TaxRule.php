<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRule extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'tax_type', 'rate', 'direction', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
