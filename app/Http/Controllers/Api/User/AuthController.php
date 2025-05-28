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
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class AuthController extends Controller

{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
    
        // Generate OTP
        $otp = rand(100000, 999999);
        $data['otp_code'] = $otp;
        $data['otp_expires_at'] = now()->addMinutes(10);
        $data['email_verified'] = false;
    
        // Make sure otp_code and otp_expires_at are fillable in User model
        $user = User::updateOrCreate(['phone' => $data['phone']], $data);
    
        \Log::info('Sending OTP email to: ' . $user->email);
    
        Mail::to($user->email)->send(new OtpMail($otp));
    
        \Log::info('Email sent to: ' . $user->email);
    
        return response()->success([
            'status' => 'otp_required',
            'message' => 'OTP sent to your email.',
            'email' => $user->email,
        ]);
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

        return response()->errors(('wrong password or email'));
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->success('logout successfully');
    }


  

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|digits:6',
        ]);
    
        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->first();
    
        if (!$user) {
            return response()->errors('Invalid OTP', 400);
        }
    
        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->errors('OTP has expired', 400);
        }
    
        // Generate a new remember_token (usually a random 60 character string)
        $rememberToken = \Str::random(60);
    
        $user->update([
            'email_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
            'remember_token' => $rememberToken,
        ]);
    
        $token = JWTAuth::fromUser($user);
    
        return response()->success('OTP verified. Registration complete.', [
            'token' => $token,
            'user' => $user,
        ]);
    }
    
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->errors('User not found', 404);
        }
    
        // Check if OTP is null
        if (is_null($user->otp_code)) {
            return response()->errors('User is already registered. OTP cannot be resent.', 400);
        }
    
        // Generate new OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);
    
        Mail::to($user->email)->send(new OtpMail($otp));
    
        return response()->success('OTP resent successfully');
    }



// forget pass 
public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->errors('User not found', 404);
        }

        // Generate a new OTP for password reset
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->success('OTP sent to your email for password reset.');
    }
public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|digits:6',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->first();

        if (!$user) {
            return response()->errors('Invalid OTP or email', 400);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->errors('OTP has expired', 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return response()->success('Password reset successfully');
    }
}