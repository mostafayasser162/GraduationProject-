<?php

namespace App\Http\Controllers;

use App\Enums\User\Role;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Enums\User\Status;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->success(['url' => $url]);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json(['message' => 'Google login failed'], 401);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'phone' => '0000000000',
                'password' => bcrypt(str()->random(16)),
                'status' => Status::APPROVED(),
                'role' => Role::USER(),
                'email_verified' => 1,
                'email_verified_at' => now(),
            ]);
        } else {
            $user->update([
                'google_id' => $googleUser->getId(),
                'email_verified' => 1,
                'email_verified_at' => now(),
            ]);
        }

        if ($user->status == Status::BLOCKED()) {
            return response()->errors(['message' => 'Account not approved'], 403);
        }

        $token = JWTAuth::fromUser($user);

        return response()->success([
            'token' => $token,
            'user' => $user,
        ]);
    }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->stateless()->user();
    //     } catch (\Exception $e) {
    //         Log::error('Google Login Error: ' . $e->getMessage());
    //         return redirect('http://localhost:5173/login?error=google_login_failed');
    //     }

    //     $user = User::where('email', $googleUser->getEmail())->first();

    //     if (!$user) {
    //         $user = User::create([
    //             'name' => $googleUser->getName(),
    //             'email' => $googleUser->getEmail(),
    //             'google_id' => $googleUser->getId(),
    //             'phone' => '0000000000',
    //             'password' => bcrypt(str()->random(16)),
    //             'status' => Status::APPROVED(),
    //             'role' => Role::USER(),
    //             'email_verified' => 1,
    //             'email_verified_at' => now(),
    //         ]);
    //     } else {
    //         $user->update([
    //             'google_id' => $googleUser->getId(),
    //             'email_verified' => 1,
    //             'email_verified_at' => now(),
    //         ]);
    //     }

    //     if ($user->status == Status::BLOCKED()) {
    //         return redirect('http://localhost:5173/login?error=account_blocked');
    //     }

    //     $token = JWTAuth::fromUser($user);

    //     // تحويل المستخدم إلى الواجهة الأمامية مع التوكن
    //     return redirect()->away("http://localhost:5173/login?token={$token}");
    // }

}
