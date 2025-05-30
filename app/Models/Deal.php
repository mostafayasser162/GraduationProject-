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
        'deposit_amount',
        'is_deposit_paid',
        'deposit_paid_at',
        'final_payment_amount',
        'is_final_paid',
        'final_paid_at',
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'is_deposit_paid' => 'boolean',
        'is_final_paid' => 'boolean',
        'deal_date' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'final_paid_at' => 'datetime',
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
