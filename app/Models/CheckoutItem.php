<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutItem extends Model
{
    protected $fillable = [
        'checkout_id', 'product_id', 'product_name', 'product_price', 'weight', 'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }
}
