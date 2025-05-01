<?php

namespace App\Rules;

use Illuminate\Support\Arr;

class Rules
{
    // Files
    const MAX_FILE_SIZE = 1024 * 1024 * 10; // 10 MB

    // Images
    const MIMES_IMAGES = 'jpeg,jpg,png,svg';

    const MAX_IMAGE_SIZE = self::MAX_FILE_SIZE;

    // Strings
    const MAX_STRING = 191;

    // Names
    const MAX_NAME = 30;

    const MIN_NAME = 3;

    public static function get($key, $default = []): array
    {
        $rules = [
            'user' => [
                'name' => ['regex:/^[A-Za-z\p{Arabic}]+(\s[A-Za-z\p{Arabic}]+)?$/u', 'max:'.self::MAX_NAME, 'min:'.self::MIN_NAME],
            ],
            'file' => [
                'image' => ['file', 'mimes:'.self::MIMES_IMAGES, 'max:'.self::MAX_IMAGE_SIZE],
            ],

            'email' => ['regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', 'max:'.self::MAX_STRING, 'email:rfc,dns'],
            'otp' => ['numeric', 'digits:4'],
            'url' => ['regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
        ];

        return Arr::get($rules, $key, $default);
    }
}
