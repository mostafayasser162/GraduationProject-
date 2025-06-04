<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Models\Order_item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StartUp\UpdateProfileRequest;
use App\Http\Resources\StartupResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $startup = auth('startup')->user()->load('products');

        $orderItems = Order_item::whereHas('product', function ($query) use ($startup) {
            $query->where('startup_id', $startup->id);
        })->with(['product', 'order' , 'order.user'])->get();

        return response()->success([
            'startup' => new StartupResource($startup),
            'orderItems' => $orderItems,
        ]);
    }

    // public function update(UpdateProfileRequest $request)
    // {
    //     $data = $request->validated();
    //     $user = $request->user();

    //     if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
    //         $file = $request->file('logo');
    //         $path = 'storage/' . $file->store('images', 'public');
    //         $data['logo'] = $path;
    //     }

    //     if (isset($data['social_media_links']) && is_array($data['social_media_links'])) {
    //         $existingLinks = $user->social_media_links ?? [];
    //         $data['social_media_links'] = array_merge($existingLinks, $data['social_media_links']);
    //     }

    //     if (!empty($data['password'])) {
    //         $data['password'] = bcrypt($data['password']);
    //     }

    //     $user->update($data);

    //     return response()->success(('Profile updated successfully.'),
    //         new StartupResource($user)
    //     );
    // }
    public function update(UpdateProfileRequest $request)
{
    $data = $request->validated();
    $user = $request->user();

    if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
        $file = $request->file('logo');
        $path = 'storage/' . $file->store('images', 'public');
        $data['logo'] = $path;
    }

    if (isset($data['social_media_links']) && is_array($data['social_media_links'])) {
        $existingLinks = $user->social_media_links ?? [];
        $data['social_media_links'] = array_merge($existingLinks, $data['social_media_links']);
    }

    if (!empty($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    }

    // Save updates to pending_update field
    $user->pending_update = $data;
    $user->save();

    return response()->success('Your profile update request has been submitted and is pending admin approval.');
}


    public function destroy(): JsonResponse
    {
        $user = Auth::user();
        $user->delete();

        return response()->success('Account deleted successfully.');
    }
}
