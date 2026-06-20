<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{

    protected $fillable = ['number', 'entry_date', 'source', 'reference', 'memo'];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
