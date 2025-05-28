<?php

namespace App\Enums\StartUps;

use App\Enums\Enum;

/**
 * @method static self PENDING()
 * @method static self APPROVED()
 * @method static self REJECTED()
 * @method static self HOLD()
 * @method static self BLOCKED()
 */
class Status extends Enum
{
    protected static function values(): array
    {
        return [
            'PENDING' => 'PENDING',
            'APPROVED' => 'APPROVED',
            'REJECTED' => 'REJECTED',
            'BLOCKED' => 'BLOCKED',
            'HOLD' => 'HOLD',
        ];
    }
    public static function allValues(): array
    {
        return array_values(static::values());
    }
    // init status
    public static function init(): string
    {
        return self::PENDING();
    }
}
