<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'payment_method_id',
        'shipping_option_id',
        'total_product_price',
        'total_shipping_cost',
        'service_fee',
        'application_fee',
        'total_purchase',
        'status'
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

    public function shippingOption()
    {
        return $this->belongsTo(ShippingOption::class);
    }

    public function items()
    {
        return $this->hasMany(CheckoutItem::class);
    }
}
