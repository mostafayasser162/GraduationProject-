<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactoryResponseResource;
use App\Models\FactoryResponse;

class ResponseController extends Controller
{
    public function index()
    {
        $factories = FactoryResponse::with('factory', 'request')->get();
        $factories = FactoryResponseResource::collection($factories);

        return response()->paginate_resource($factories);
    }

    public function show($id)
    {
        $response = FactoryResponse::with('factory', 'request')->findOrFail($id);
        if (!$response) {
            return response()->errors('Response not found');
        }
        $factory = new FactoryResponseResource($response);
        return response()->success($factory);
    }
}
