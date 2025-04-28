<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = [
        'url',
        'is_main',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
