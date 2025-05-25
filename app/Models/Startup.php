<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Startup extends Model
{
    use SoftDeletes ,HasFactory;
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
    ];
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
}
