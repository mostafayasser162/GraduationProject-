<?php

namespace App\Models\Traits;

use App\Enums\Company\Type;
use App\Enums\User\Role;
use App\Http\Resources\Driver\DriverResource;
use App\Http\Resources\Supplier\SupplierResource;
use App\Http\Resources\User\UserResource;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

trait Actor // Person who acts in the system
{
    use HasApiTokens, HasFactory , SoftDeletes ;

    public function isAdmin(): bool
    {
        // check that this is User::class
        return class_basename($this) == 'User' &&
            ($this->role == Role::ADMIN());
    }

    public function isOwner(): bool
    {
        return class_basename($this) == 'User'
            && $this->role == Role::OWNER();
    }

    public function isUser(): bool
    {
        return class_basename($this) == 'User'
            && $this->role == Role::USER();
    }
    public function isEmployee(): bool
    {
        return class_basename($this) == 'User'
            && $this->role == Role::EMPLOYEES();
    }
    public function isWorker(): bool
    {
        return $this->isOwner() || $this->isEmployee();
    }
    public function isInvestor(): bool
    {
        return class_basename($this) == 'User'
            && $this->role == Role::INVESTOR();
    }
    // public function matchOTP(int $otp): bool
    // {
    //     return $this->otp == $otp
    //         || (\Str::startsWith($this->phone, '53711') && $otp == '5555');
    // }

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

    // public function getAvatarAttribute($value)
    // {
    //     return ! $value
    //         ? 'https://ui-avatars.com/api/?name='.str_replace(' ', '+', $this->name).'&color=fda738&background=f5f5f5&rounded=true&?font-size=0.36'
    //         : $value;
    // }

    // public function resource(): string
    // {
    //     if ($this->isDriver()) {
    //         return DriverResource::class;
    //     }

    //     if ($this->isSupplier()) {
    //         return SupplierResource::class;
    //     }

    //     return UserResource::class;
    // }

    /**
     * Specifies the user's FCM token
     */
    // public function routeNotificationForFcm(): array|string
    // {
    //     return $this->devices
    //         ->whereNotNull('fcm_token')
    //         ->where('logged_in', true)
    //         ->pluck('fcm_token')
    //         ->toArray();
    // }

}
