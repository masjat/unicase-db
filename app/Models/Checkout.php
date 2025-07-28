<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CheckoutItem;


class Checkout extends Model
{
    protected $fillable = [
        'user_id', 'shipping_address_id', 'payment_method_id',
        'courier', 'courier_service', 'shipping_cost',
        'subtotal', 'total', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function items()
    {
        return $this->hasMany(CheckoutItem::class);
    }
    public function payment()
    {
         return $this->hasOne(Payment::class); 
    }
}
