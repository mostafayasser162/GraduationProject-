<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\FactoryResponse\Status;

class FactoryResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_id',
        'request_id',
        'description',
        'price',
        'image',
        'status',
        'estimated_delivery_time',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function request()
    {
        return $this->belongsTo(request::class);
    }
    
    public function deal()
    {
        return $this->hasOne(Deal::class);
    }
}
