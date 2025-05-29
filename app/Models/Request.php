<?php

namespace App\Models;

use App\Enums\Request\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\SearchScope;
use App\Models\Scopes\SortScope;
class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'description',
        'image',
        'delivery_date',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new SearchScope);
        static::addGlobalScope(new SortScope);
    }
}
