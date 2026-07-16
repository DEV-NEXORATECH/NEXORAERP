<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'number', 'entry_date', 'source', 'reference', 'memo'];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
