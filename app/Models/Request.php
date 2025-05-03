<?php

namespace App\Models;

use App\Enums\Request\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
