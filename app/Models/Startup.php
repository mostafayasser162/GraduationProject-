<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; // ADD THIS
use Illuminate\Foundation\Auth\User as Authenticatable;

class Startup extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory, HasApiTokens;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo',
        'social_media_links',
        'phone',
        'status',
        'package_id',
        'categories_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'email',
        'password',
    ];
    protected $hidden = ['password'];

    protected $casts = [
        'social_media_links' => 'array',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
    public function isStartup(): bool
    {
        return class_basename($this) == 'Startup';
    }

    // public function joinRequests()
    // {
    //     return $this->hasMany(StartupJoinRequest::class);
    // }
    protected static function booted(): void
    {
        static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    //relation ship with order from startup it at product id    
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,        // النهاية: الطلبات
            Order_item::class,   // الوسيط: العناصر
            'product_id',        // المفتاح في Order_item الذي يشير إلى المنتج
            'id',                // المفتاح في Order (غالبًا يكون id)
            'id',                // المفتاح في Startup (أي المنتج مرتبط بستارت أب عن طريق startup_id في product)
            'order_id'           // المفتاح في Order_item الذي يشير إلى الطلب
        )->whereHas('product', function ($query) {
            $query->whereColumn('products.startup_id', 'startups.id');
        });
    }

    public function sizes()
{
    return $this->hasMany(Size::class);
}

}
