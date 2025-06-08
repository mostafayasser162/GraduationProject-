<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'startup_id',
        'name',
        'description',
        'price',
        'stock',
        'discount_percentage',
        'sub_category_id',
    ];


    public function startup()
    {
        return $this->belongsTo(Startup::class, 'startup_id', 'id')->withTrashed();
    }
    
    public function subCategory()
    {
        return $this->belongsTo(Sub_category::class, 'sub_category_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function mainImage()
    {
        return $this->hasOne(Image::class)->where('is_main', 1);
    }

    public function inCarts()
    {
        return $this->belongsToMany(User::class, 'cart_product')
            ->withPivot('quantity', 'product_size_id')
            ->withTimestamps();
    }

    // public function productSizes()
    // {
    //     return $this->hasMany(Product_size::class);
    // }

    // In App\Models\Product.php
    public function productSize()
    {
        return $this->belongsToMany(Product_size::class, 'cart_product')
            ->withPivot('quantity', 'product_size_id', 'product_id')
            ->withTimestamps();
        // ->withTimestamps();
    }

    // protected static function booted(): void
    // {
    //     static::addGlobalScope(new SearchScope);
    //     static::addGlobalScope(new SortScope);
    // }
    // public function variants()
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
    public function sizes()
    {
        // return $this->hasMany(Product_size::class);
        return $this->hasMany(Product_size::class, 'product_id');
    }
    public function colors()
    {
        return $this->hasMany(Product_colors::class);
    }
    public function Order_item()
    {
        return $this->hasMany(Order_item::class);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('id', 'like', "%{$term}%")
                ->orWhereHas('startup', function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                    
                });
        });
    }
    

    public function scopeBestSellers($query)
    {
        return $query->withSum('Order_item as total_quantity_sold', 'quantity')
            ->orderByDesc('total_quantity_sold')
            ->orderBy('created_at', 'desc'); // Secondary sort by creation date
    }
    // This scope retrieves the latest products based on their creation date

    public function scopeNewArrivals($query)
    {
        return $query->orderBy('created_at', 'desc');
    }


}
