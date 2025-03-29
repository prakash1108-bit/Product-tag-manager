<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'product_name', 
        'description', 
        'price', 
        'sku', 
        'quantity', 
        'type', 
        'vendor', 
        'image',
        'tags'
    ];

    public function getProductImageUrlAttribute()
    {
        return asset('images/products/' . $this->image);
    }
}
