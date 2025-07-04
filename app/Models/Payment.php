<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'checkout_id', 'payment_method_id',
        'order_id', 'payment_type', 'bank', 'va_number',
        'amount', 'status', 'expired_at'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function checkout() { return $this->belongsTo(Checkout::class); }
    public function method() { return $this->belongsTo(PaymentMethod::class, 'payment_method_id'); }
}

