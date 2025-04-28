<?php

namespace App\Models;

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
}
