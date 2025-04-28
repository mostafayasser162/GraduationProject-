<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_size extends Model
{
    //
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'price',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Product_colors::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
