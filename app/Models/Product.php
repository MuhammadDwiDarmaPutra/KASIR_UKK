<?php

namespace App\Models;

// use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'image',
        'stock',
        'price',
    ];

    public function cart(){
        return $this->hasMany(Product::class, 'product_id');
    }

    public function details(){
        return $this->HasMany(DetailOrder::class, 'product_id');
    }
}
