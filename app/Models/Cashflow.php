<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashflow extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'type',
        'category',
        'expense_category_id',
        'bank_account_id',
        'cost_type',
        'vendor',
        'description',
        'amount',
        'transaction_date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
