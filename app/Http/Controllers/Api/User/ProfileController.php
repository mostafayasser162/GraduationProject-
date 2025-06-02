<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth('api')->user()->load('orders' , 'addresses');

        return response()->success(
            new UserResource($user)
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $user->update($data);

        return response()->success(('Profile updated successfully.'),
            new UserResource($user)
        );
    }

    public function destroy(): JsonResponse
    {
        $user = Auth::user();
        $user->delete();

        return response()->success('Account deleted successfully.');

    }
}
