<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = ['name', 'bank_name', 'account_number', 'opening_balance'];
}
