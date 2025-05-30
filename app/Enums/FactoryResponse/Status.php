<?php

namespace App\Enums\FactoryResponse;

use App\Enums\Enum;

/**
 * @method static self PENDING()
 * @method static self ACCEPTED()
 * @method static self REJECTED()
 * @method static self DONE()
 */
class Status extends Enum
{
    protected static function values(): array
    {
        return [
            'PENDING' => 'PENDING',
            'ACCEPTED' => 'ACCEPTED',
            'REJECTED' => 'REJECTED',
            'DONE' => 'DONE'
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
