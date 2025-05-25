<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\StartupResource;
use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index(Request $request)
    {

        $query = Startup::with('user' , 'products');

        $startUps = $query->paginate();
        $startUps = StartupResource::collection($startUps);

        return response()->paginate_resource($startUps);
    }

    public function show($id)
    {
        $startUp = Startup::with('user' , 'products')->find($id);

        if (!$startUp) {
            return response()->errors('startUp not found');
        }
        return response()->success(new StartupResource($startUp));
    }

}
