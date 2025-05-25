<?php

namespace App\Models;

use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    //
    protected $fillable = [
        'startup_id',
        'size',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

        protected static function booted(): void
    {
        // static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
