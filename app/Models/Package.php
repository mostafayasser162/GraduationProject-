<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
