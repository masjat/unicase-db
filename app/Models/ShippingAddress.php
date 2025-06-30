<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'receivers_name',
        'phone_number',
        'address_label',
        'city',
        'postal_code',
        'full_address',
        'note_to_courier'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
