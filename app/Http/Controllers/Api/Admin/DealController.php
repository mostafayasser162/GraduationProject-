<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Http\Resources\DealResource;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['request.startup', 'factory', 'factoryResponse'])->paginate();
        return response()->paginate_resource(DealResource::collection($deals));
    }

    public function show($id)
    {
        $deal = Deal::with(['request.startup', 'factory', 'factoryResponse'])->find($id);
        if (!$deal) {
            return response()->errors('Deal not found');
        }
        return response()->success(new DealResource($deal));
    }
}
