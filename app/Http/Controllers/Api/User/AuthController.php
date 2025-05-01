<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Helper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JWTAuth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::withTrashed()->updateOrCreate(['phone' => $data['phone']], $data);

        $token = JWTAuth::fromUser($user);

        $masked_email = Helper::maskEmail($user->email);

        $responseData = [
            'user'  => $user,
            'token' => $token,
        ];

        return response()->success(__('auth.send_otp_message_successfully'), $responseData, [
            'email' => $masked_email,
        ]);
    }
}
