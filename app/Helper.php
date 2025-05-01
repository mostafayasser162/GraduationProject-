<?php

namespace App;

use App\Notifications\General\OtpNotification;
use Illuminate\Support\Str;

class Helper
{
//     public static function sendOtp($user, $prefix = null)
//     {
//         if (in_array(config('app.env'), ['local', 'staging', 'development'])
// //            && $user != '537118696'
//             && ! Str::contains($user->email, 'lockers')
//         ) {
//             return 5555;
//         }
//         $otp_length = config('auth.otp_length');
//         $otp = mt_rand(pow(10, $otp_length - 1), pow(10, $otp_length) - 1); // 1000 - 9999

//         if ($prefix) {
//             $user = $prefix.$user;
//         }

//         // $user->notify(new OtpNotification($otp));

//         return $otp;
//     }

    public static function toArray($string)
    {

        return explode($string, ',');
    }

    public static function maskEmail($email, $char = '*', $mask = 3): string
    {
        $email = Str::of($email);
        $username = $email->before('@');
        $domain = $email->after('@');
        $masked = Str::mask($username, $char, $mask);

        return "{$masked}@{$domain}";
    }
}
