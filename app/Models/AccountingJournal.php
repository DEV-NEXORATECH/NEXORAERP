<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingJournal extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'code', 'name', 'type', 'default_chart_account_id', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function defaultAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'default_chart_account_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'journal_id');
    }
}
