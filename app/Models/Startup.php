<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    protected $fillable = ['user_id', 'name', 'category'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

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
