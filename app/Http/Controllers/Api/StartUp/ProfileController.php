<?php

namespace App\Http\Controllers\Api\StartUp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StartUp\UpdateProfileRequest;
use App\Http\Resources\StartupResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $startup = auth('startup')->user();

        $orders = Order::whereHas('orderItems.product', function ($query) use ($startup) {
            $query->where('startup_id', $startup->id);
        })->get();

        return response()->success([
            'startup' => new StartupResource($startup),
            'orders' => $orders,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
            $path = 'storage/' . $file->store('images', 'public');
            $data['logo'] = $path;
        }
        $user->update($data);

        return response()->success(('Profile updated successfully.'),
            new StartupResource($user)
        );
    }

    public function destroy(): JsonResponse
    {
        $user = Auth::user();
        $user->delete();

        return response()->success('Account deleted successfully.');
    }
}
