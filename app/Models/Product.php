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
        'sub_category_id',
    ];


    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(Sub_category::class, 'sub_category_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    // public function variants()
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }
}
