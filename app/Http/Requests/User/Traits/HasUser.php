<?php

namespace App\Http\Requests\User\Traits;

use App\Enums\User\Role;
use App\Models\User;

trait HasUser
{
    public function __construct(public ?User $user = null)
    {
        parent::__construct();
    }

    // before validation
    protected function prepareForValidation(): void
    {

        $this->user = auth('api')->check() ? auth('api')->user() : User::where([
            'phone' => $this->integer('phone'),
        ])
            ->whereRole(Role::USER())
            ->withTrashed()->first();
        parent::prepareForValidation();
    }
}
