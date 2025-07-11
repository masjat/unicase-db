<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'image', 'rating','color','category_id','weight'
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}
    public function reviews() 
{
    return $this->hasMany(Review::class);
}
    public function images()
{
    return $this->hasMany(ProductImage::class);
}





}


