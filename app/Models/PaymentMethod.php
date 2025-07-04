<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'type'];

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
