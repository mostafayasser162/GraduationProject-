<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\User\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
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
            'email' => $masked_email,
        ];

        return response()->success(('you have been register successfully'), $responseData);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        if (auth('api')->attempt($validatedData)) {
            $user = auth('api')->user();
            
            if ($user->status == Status::BLOCKED()) {
                return response()->errors('Your Account have been blocked');
            }

            $token = JWTAuth::fromUser($user);
            $type = match ($user->role) {
                'ADMIN' => 'admin',
                'USER' => 'user',
                'OWNER' => 'owner',
                'EMPLOYEES' => 'employee',
            };
            $data = [
                $type => $user,
                'role' => $user->role,
                'token' => $token,
            ];
            return response()->success(('logged in succ'), $data);
        }

        return response()->errors(('invalid_data'));
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->success('logout successfully');
    }


}
