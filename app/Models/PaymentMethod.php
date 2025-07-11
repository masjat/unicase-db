<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
      protected $fillable = [
        'name',
        'account_number',
        'account_name',
        'bank_logo',
        'type',
        'is_active',
    ];

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
