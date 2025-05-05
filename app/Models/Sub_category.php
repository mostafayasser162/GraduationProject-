<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_category extends Model
{
  
    protected $fillable = [
        'name',
        'category_id',
    ];
    // protected $table = 'sub_categories';
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
