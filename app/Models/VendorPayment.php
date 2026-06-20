<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPayment extends Model
{
    use SoftDeletes;

    protected $fillable = ['vendor_bill_id', 'bank_account_id', 'cashflow_id', 'amount', 'payment_date', 'reference'];

    public function vendorBill(): BelongsTo
    {
        return $this->belongsTo(VendorBill::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function cashflow(): BelongsTo
    {
        return $this->belongsTo(Cashflow::class);
    }
}
