<?php

namespace App\Models;

use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class Sub_category extends Model
{

    protected $fillable = [
        'name',
        'category_id',
        'image',
    ];
    // protected $table = 'sub_categories';
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
        protected static function booted(): void
    {
        static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
