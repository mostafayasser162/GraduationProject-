<?php

namespace App\Http\Controllers\Api\Factory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Factory\UpdateProfileRequest;
use App\Http\Resources\FactoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{
    public function index()
    {
        $factory = auth()->user();

        $factory->loadCount(['deals'])->load(['ratings']);

        return response()->success(new FactoryResource($factory));
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $factory = auth()->user();

        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $factory->update($data);

        return response()->success(new FactoryResource($factory));
    }

    public function destroy()
    {
        $factory = Auth::guard('factory')->user();

        $factory->update([
            'status' => \App\Enums\Factory\Status::DELETED(),
        ]);
        $factory->delete(); // soft delete

        return response()->success('Account deleted successfully.');
    }
}
