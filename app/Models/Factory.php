<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\Factory\Status;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Factory extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable , HasApiTokens ;

    protected $table = 'factories';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'payment_methods',
        'payment_account',
        'status',
        'description',
    ];

    protected $hidden = [
        'password',
    ];

    public function isFactory(): bool
    {
        return class_basename($this) == 'Factory';
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
