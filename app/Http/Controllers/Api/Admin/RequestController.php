<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\Models\Request as RequestModel;
class RequestController extends Controller
{

    public function index()
    {
        $requests = RequestModel::with(['startup'])
            ->get();
        $requests = RequestResource::collection($requests);
        return response()->paginate_resource($requests);
    }

    public function show($id)
    {

        $request = RequestModel::where('id', $id)
            ->with(['startup'])
            ->first();

        if (!$request) {
            return response()->errors('request not found');
        }

        return response()->success([
            'Request' => new RequestResource($request)
        ]);
    }
}
