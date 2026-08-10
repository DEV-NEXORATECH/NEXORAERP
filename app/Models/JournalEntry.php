<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use BelongsToCompany, RecordsAudit;

    protected $fillable = ['company_id',
        'number', 'journal_id', 'entry_date', 'source', 'reference', 'currency_id', 'exchange_rate', 'memo', 'is_posted', 'posting_date'];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:6',
            'is_posted' => 'boolean',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(AccountingJournal::class, 'journal_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
