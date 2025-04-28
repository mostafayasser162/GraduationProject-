<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product_colors extends Model
{
    protected $fillable = [
        'product_id',
        'color_name',
        'color_code',
    ];

    //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
