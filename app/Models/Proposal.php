<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'number', 'title', 'status', 'amount', 'scope', 'valid_until', 'signed_file_path'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
