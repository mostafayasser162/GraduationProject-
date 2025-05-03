<?php

namespace App\Http\Controllers\Api\Factory;

use App\Enums\Request\Status;
use App\Http\Controllers\Controller;
use App\Models\Startup;
use Illuminate\Http\Request;

class StartupRequestController extends Controller
{
    public function index(Request $request)
    {
        // $factory = auth('factory')->user();
        $query = \App\Models\Request::where('status' , Status::PENDING())->with('Startup')->paginate();

        // Todo: dol lel admin
        // if ($request->filled('status') && in_array($request->status, \App\Enums\Request\Status::allValues())) {
        //     $query->where('status', $request->status);
        // }

        return response()->success($query);
    }

    public function show(\App\Models\Request $request)
    {
        $request->load('Startup');
        return response()->success($request);
    }


}
