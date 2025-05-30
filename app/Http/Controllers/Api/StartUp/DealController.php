<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Http\Resources\DealResource;

class DealController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $deals = Deal::with(['request.startup', 'factoryResponse'])
            ->whereHas('request', function ($query) use ($user) {
                $query->where('startup_id', $user->id);
            })
            ->get();

        return response()->paginate_resource(DealResource::collection($deals));
    }

    public function show($id)
    {
        $user = auth()->user();
        $deal = Deal::with(['request.startup', 'factoryResponse'])
            ->where('id', $id)
            ->whereHas('request', function ($query) use ($user) {
                $query->where('startup_id', $user->id);
            })
            ->first();
        
        if (!$deal) {
            return response()->errors('Deal not found');
        }
        return response()->success(new DealResource($deal));
    }
}
