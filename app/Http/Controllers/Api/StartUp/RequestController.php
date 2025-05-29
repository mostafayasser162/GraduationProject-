<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $requests = RequestModel::where('startup_id', $user->id)
            ->with(['startup'])
            ->get();
        $requests = RequestResource::collection($requests);
        return response()->paginate_resource($requests);
    }

    public function show($id)
    {
        $user = auth()->user();

        $request = RequestModel::where('id', $id)
            ->where('startup_id', $user->id)
            ->with(['startup'])
            ->firstOrFail();

        if (!$request) {
            return response()->errors('request not found');
        }

        return response()->success([
            'Request' => new RequestResource($request)
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $request = RequestModel::where('id', $id)
            ->where('startup_id', $user->id)
            ->firstOrFail();
        if (!$request) {
            return response()->errors('request not found');
        }

        $request->delete();

        return response()->success('Request deleted successfully');
    }
}
