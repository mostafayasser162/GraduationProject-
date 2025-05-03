<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FactoryResponse;
class ResponseController extends Controller
{
    public function index()
    {
        $factories = FactoryResponse::with('factory' , 'request')->paginate();

        return response()->success($factories);
    }

    public function show($id)
    {
        $response = FactoryResponse::with('factory', 'request')->findOrFail($id);

        return response()->success($response);
    }
}
