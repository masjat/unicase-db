<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOption extends Model
{
    protected $fillable = ['name', 'price', 'estimate_days', 'estimate_label'];
}
