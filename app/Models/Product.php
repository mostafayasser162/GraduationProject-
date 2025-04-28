<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'startup_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image'

    ];

    // public function startup()
    // {
    //     return $this->belongsTo(Startup::class);
    // }

    // public function variants()
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }
}
