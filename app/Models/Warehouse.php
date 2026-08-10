<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id', 'code', 'name', 'address', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function locations(): HasMany
    {
        return $this->hasMany(StockLocation::class);
    }
}
