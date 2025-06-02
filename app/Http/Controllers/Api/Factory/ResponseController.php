<?php

namespace App\Http\Controllers\Api\Factory;

use App\Models\Request as RequestModel;
use App\Models\FactoryResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Factory\SendFactoryResponseRequest;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function sendOffer(SendFactoryResponseRequest $request, $id)
    {
        $data = $request->validated();
        $factory = auth()->user();
        $data['request_id'] = $id;
        $requestModel = RequestModel::find($id);
        
        if (!$requestModel) {
            return response()->errors('Request not found');
        }

        $alreadyResponded = FactoryResponse::where('factory_id', $factory->id)
            ->where('request_id', $data['request_id'])
            ->exists();

        if ($alreadyResponded) {
            return response()->errors('You have already sent a response to this request');
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('responses', 'public');
            $data['image'] = $path;
        }

        $data['factory_id'] = $factory->id;

        $response = FactoryResponse::create($data);

        return response()->success($response, 'Offer sent successfully');
    }

    public function index(Request $request)
    {

        $factory = auth()->user();
        $factories = FactoryResponse::where('factory_id', $factory->id)->get();

        return response()->success($factories);
    }

    public function show($id)
    {
        $response = FactoryResponse::find($id);

        if (!$response || $response->factory_id !== auth()->id()) {
            return response()->errors('Response not found.');
        }

        return response()->success($response);
    }
    public function destroy($id)
    {
        dd(auth()->user()->isFactory());
        $response = FactoryResponse::find($id);

        if (!$response || $response->factory_id !== auth()->id()) {
            return response()->errors('Response not found.');
        }

        $response->delete();

        return response()->success('Response deleted successfully');
    }
}
