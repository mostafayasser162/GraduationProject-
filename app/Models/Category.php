<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }

    public function startups()
    {
        return $this->hasMany(Startup::class, 'categories_id');
    }
}
