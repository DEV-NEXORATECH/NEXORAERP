<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'category', 'contact_name', 'email', 'phone', 'payment_terms', 'status'];
}
