<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{

    protected $fillable = [
        'name', 'asset_code', 'category', 'purchase_date', 'purchase_cost',
        'residual_value', 'useful_life_years', 'depreciation_method',
        'monthly_depreciation', 'accumulated_depreciation', 'book_value',
        'status', 'notes',
    ];
}
