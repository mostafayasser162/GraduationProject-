<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_size extends Model
{
    //
    protected $fillable = [
        'product_id',
        'size_id',
        'price',
        'stock',
        'discount_percentage'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
