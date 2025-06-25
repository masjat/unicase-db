<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'address_id',
        'id_shipping',
        'service_fee',
        'total_price',
        'id_status',
    ];

    // Jika tidak menggunakan timestamps otomatis Laravel (created_at dan updated_at)
    // public $timestamps = false;

    // Relasi (jika kamu punya model terkait)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'id_shipping');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }
}