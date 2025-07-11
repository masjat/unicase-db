<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'receivers_name',
        'phone_number',
        'province_id',
        'province_name',
        'city_id',
        'city',
        'district_id',
        'district_name',
        'postal_code',
        'full_address',
        'note_to_courier',
        'is_primary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
