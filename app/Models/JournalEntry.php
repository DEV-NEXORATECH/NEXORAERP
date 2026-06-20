<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = ['number', 'entry_date', 'source', 'reference', 'memo'];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
