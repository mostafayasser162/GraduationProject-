<?php

namespace App\Enums\Factory;

use App\Enums\Enum;

/**
 * @method static self APPROVED()
 * @method static self BLOCKED()
 * @method static self DELETED()
 * @method static self HOLD()
 */
class Status extends Enum
{
    protected static function values(): array
    {
        return [
            'APPROVED' => 'APPROVED',
            'BLOCKED' => 'BLOCKED',
            'DELETED' => 'DELETED',
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
        return self::APPROVED();
    }
}
