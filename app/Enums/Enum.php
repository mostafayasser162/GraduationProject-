<?php

namespace App\Enums;

use Illuminate\Support\Str;
use Spatie\Enum\Laravel\Enum as BaseEnum;

class Enum extends BaseEnum
{
    public static function toResponse($value = null): array
    {
        $className = substr(strrchr(static::class, '\\'), 1); // Extract class name
        $namespaceParts = explode('\\', static::class); // Split namespace into parts
        $folderName = Str::snake($namespaceParts[count($namespaceParts) - 2]);
        // Construct dynamic string
        $tkey = "enums.$folderName.$className";

        // add it to Trait or abstract class
        if (! $value) {
            return array_values(array_map(function ($item) use ($tkey) {
                $tkey = strtolower("$tkey.$item");

                return [
                    'id' => $item,
                    'name' => __($tkey),
                ];
            }, self::toArray()));
        }
        if (! isset(self::toArray()[$value])) {
            throw new \InvalidArgumentException("Value {$value} is not part of the enum ".static::class);
        }

        $tkey = strtolower("$tkey.$value");

        return [
            'id' => $value,
            'name' => __($tkey),
        ];
    }
}
