<?php

namespace App\Models;

use App\Models\Scopes\SortScope;
use App\Enums\FactoryResponse\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

        protected static function booted(): void
    {
        // static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
