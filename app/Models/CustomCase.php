<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CaseType;
use App\Enums\PrintEffect;

class CustomCase extends Model
{
    protected $fillable = [
        'user_id', 'case_type', 'print_effect',
        'brand_id', 'brand_type_id', 'description', 'image_url','price_case', 'price_print', 'total_price'
    ];

    protected $casts = [
        'case_type' => CaseType::class,
        'print_effect' => PrintEffect::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandType()
    {
        return $this->belongsTo(BrandType::class);
    }
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
