<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\RecordsAudit;

class Vendor extends Model
{
    use BelongsToCompany, RecordsAudit;
    protected $fillable = ['company_id',
        'name', 'category', 'contact_name', 'email', 'phone', 'payment_terms', 'status'];

    public function purchaseOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function bills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VendorBill::class, 'vendor_name', 'name');
    }

    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProcurementContract::class);
    }
}
