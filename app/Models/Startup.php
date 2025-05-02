<?php

namespace App\Models;

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

    // public function requests()
    // {
    //     return $this->hasMany(StartupRequest::class);
    // }

    // public function joinRequests()
    // {
    //     return $this->hasMany(StartupJoinRequest::class);
    // }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
    
}
