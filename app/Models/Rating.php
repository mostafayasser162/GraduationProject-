<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'factory_id',
        'deal_id',
        'rate',
        'comment',
    ];


    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
