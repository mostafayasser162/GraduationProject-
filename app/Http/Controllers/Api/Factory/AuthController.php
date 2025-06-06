<?php

namespace App\Http\Controllers\Api\Factory;

use App\Enums\Factory\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Factory\LoginRequest;
use App\Models\Factory;
use JWTAuth;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $factory = Factory::where('phone', $data['phone'])->whereNull('deleted_at')->first();

        if (!$factory || !\Hash::check($data['password'], $factory->password)) {
            return response()->errors('wrong password or email');
        }

        if ($factory->status == Status::BLOCKED()) {
            return response()->errors('this factory have been blocked');
        }

        $token = JWTAuth::fromUser($factory);

        $data = [
            'factory' => $factory,
            'token' => $token,
        ];

        return response()->success('logged in successfully', $data);
    }
}
