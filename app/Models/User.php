<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Traits\Actor;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function startup()
    {
        return $this->hasOne(Startup::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    protected static function booted(): void
    {
        // static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%$term%")
                ->orWhere('id', 'like', "%$term%");
        });
    }
    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cart_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    // public function wishlist()
    // {
    //     return $this->hasMany(Wishlist::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

}
