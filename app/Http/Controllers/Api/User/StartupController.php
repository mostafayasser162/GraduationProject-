<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index(Request $request)
    {

        $query = Startup::with('user');

        $startUps = $query->paginate();

        return response()->success($startUps);
    }

    public function show($id)
    {
        $startUp = Startup::with('user' , 'products')->find($id);

        if (!$startUp) {
            return response()->errors('startUp not found');
        }
        return response()->success($startUp);
    }

}
