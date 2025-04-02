<?php

namespace App\Enums\User;

use App\Enums\Enum;

/**
 * @method static self ADMIN()
 * @method static self USER()
 * @method static self OWNER()
 * @method static self EMPLOYEES()
 * @method static self INVESTOR()
 */
class Role extends Enum
{
    protected static function values(): array
    {
        return [
            'ADMIN' => 'ADMIN',
            'USER' => 'USER',
            'OWNER' => 'OWNER',
            'EMPLOYEES' => 'EMPLOYEES',
            'INVESTOR' => 'INVESTOR',
        ];
    }
}
