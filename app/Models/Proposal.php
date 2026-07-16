<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use BelongsToCompany, RecordsAudit;
    use SoftDeletes;

    protected $fillable = ['company_id',
        'project_id', 'number', 'title', 'status', 'amount', 'scope', 'valid_until', 'signed_file_path'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
