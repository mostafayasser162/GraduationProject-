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
        'payment_method',
        'payment_account',
        'trial_ends_at'
    ];
    protected $hidden = ['password'];

    protected $casts = [
        'social_media_links' => 'array',
        'deleted_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
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
        )
        ->whereHas('product', function ($query) {
            $query->whereColumn('products.startup_id', 'startups.id');
        });
    }
    // public function getOrdersAttribute()
    // {
    //     $orders = Order::whereHas('orderItems.product', function ($query) {
    //         $query->where('startup_id', $this->id);
    //     })->with(['orderItems.product'])->get();
    
    //     // فلترة order_items لكل order لإبقاء فقط المنتجات الخاصة بهذا الـ startup
    //     $orders->each(function ($order) {
    //         $order->order_items = $order->order_items
    //             ->filter(function ($item) {
    //                 return $item->product && $item->product->startup_id == $this->id;
    //             })
    //             ->values(); // reset keys
    //     });
    //     return $orders->filter(function ($order) {
    //         return $order->order_items->isNotEmpty();
    //     })->values();
    // }
    


    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    // function to get the total revenue of the startup
    public function getTotalRevenueAttribute()
    {
        return Order_item::with('product')
            ->whereHas('product', function ($query) {
                $query->where('startup_id', $this->id);
            })
            ->sum('price');
    }
    
    
}
