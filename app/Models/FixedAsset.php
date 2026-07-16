<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;

class FixedAsset extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'name', 'asset_code', 'category', 'purchase_date', 'purchase_cost',
        'residual_value', 'useful_life_years', 'depreciation_method',
        'monthly_depreciation', 'accumulated_depreciation', 'book_value',
        'status', 'notes',
    ];
}
