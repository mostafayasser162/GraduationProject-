<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth('api')->user()->load([
            'orders.orderItems.product' => function ($query) {
            $query->with(['subCategory', 'images']);
            },
            'addresses'
        ]);
        // $user = auth()->user();
        // $user = Order::with([ 'orders' , 'addresses' ,'orderItems.product', 'orderItems', 'orderItems.productSize'])
        //     ->where('user_id', $user->id)
        //     ->latest()
        //     ->get();

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
