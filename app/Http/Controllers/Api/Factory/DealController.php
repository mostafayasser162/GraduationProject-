<?php

namespace App\Http\Controllers\Api\Factory;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Http\Resources\DealResource;

class DealController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // dd($user);
        $deals = Deal::with(['request.startup', 'factoryResponse'])
            ->where('factory_id', $user->id)
            ->get();
        if (!$deals) {
            return response()->errors('No deals found');
        }
        return response()->paginate_resource(DealResource::collection($deals));
    }

    public function show($id)
    {
        $user = auth()->user();
        $deal = Deal::with(['request.startup', 'factoryResponse'])
            ->where('id', $id)
            ->where('factory_id', $user->id)
            ->first();

        if (!$deal) {
            return response()->errors('Deal not found');
        }
        return response()->success(new DealResource($deal));
    }
}
