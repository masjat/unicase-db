<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'payment_method_id',
        'amount',
        'transfer_proof',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relasi
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
