<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'factory_id',
        'price',
        'deal_date',
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'deal_date' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
    
    public function factoryResponse()
    {
        return $this->belongsTo(FactoryResponse::class);
    }
}
