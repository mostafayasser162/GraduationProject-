<?php

namespace App\Models;

use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
        protected static function booted(): void
    {
        // static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
