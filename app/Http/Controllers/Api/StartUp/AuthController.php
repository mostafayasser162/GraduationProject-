<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Enums\StartUps\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\StartUp\LoginRequest;
use App\Http\Requests\StartUp\RegisterRequest;
use App\Models\Startup;
use JWTAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $startup = Startup::where('email', $data['email'])->first();
        if ($startup->status == Status::BLOCKED()) {
            return response()->errors('this startup have been blocked');
        }
        if ($startup->status == Status::PENDING()) {
            return response()->errors('this startup request still under processing ');
        }
        if ($startup->status == Status::REJECTED()) {
            return response()->errors('this startup request has been rejected');
        }

        if (!$startup || !\Hash::check($data['password'], $startup->password)) {
            return response()->errors('invalid_data');
        }
        $token = JWTAuth::fromUser($startup);

        $data = [
            'startup' => $startup,
            'token' => $token,
        ];

        return response()->success('logged in successfully', $data);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = \Hash::make($data['password']);
        $data['status'] = Status::init();
        $data['user_id'] = auth()->user()->id;
// image code
        $file = $data['logo'];
        $path = 'storage/'. $file->store('images', 'public');
        $data['logo'] = $path;
// end image code

        Startup::updateOrCreate(['email' => $data['email']], $data);

        return response()->success('Startup registered request has been send successfully');
    }
}
