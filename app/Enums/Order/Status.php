<?php

namespace App\Enums\Order;

use App\Enums\Enum;

/**
 * @method static self PENDING()
 * @method static self PAID()
 * @method static self SHIPPED()
 * @method static self DELIVERED()
 * @method static self CANCELED()
 */
class Status extends Enum
{
    protected static function values(): array
    {
        return [
            'PENDING' => 'PENDING',
            'PAID' => 'PAID',
            'SHIPPED' => 'SHIPPED',
            'DELIVERED' => 'DELIVERED',
            'CANCELED' => 'CANCELED',

        ];
    }

    // init status
    // public static function init(): string
    // {
    //     return self::PENDING();
    // }
}
