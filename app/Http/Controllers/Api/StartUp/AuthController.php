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
        //if the startup is soft deleted
        if (!$startup) {
            return response()->errors('startup does not exist.');
        }

        if (!\Hash::check($data['password'], $startup->password)) {
            return response()->errors('wrong password or email');
        }

        if ($startup->status == Status::BLOCKED()) {
            return response()->errors('this startup have been blocked');
        }
        if ($startup->status == Status::PENDING()) {
            return response()->errors('this startup request still under processing ');
        }
        if ($startup->status == Status::REJECTED()) {
            return response()->errors('this startup request has been rejected');
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
        $file = $data['commercial_register'];
        $path = 'storage/' . $file->store('commercial_register', 'public');
        $data['commercial_register'] = $path;
        // end commercial_register code

        Startup::updateOrCreate(['email' => $data['email']], $data);

        return response()->success('Startup registered request has been send successfully');
    }
}
