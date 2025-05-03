<?php

namespace App\Http\Controllers\Api\Factory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Factory\SendFactoryResponseRequest;
use App\Models\FactoryResponse;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function sendOffer(SendFactoryResponseRequest $request)
    {
        $data = $request->validated();
        $factory = auth()->user();

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
        $factories = FactoryResponse::where('factory_id', $factory->id)->paginate();

        return response()->success($factories);

    }

    public function show($id)
    {
        $response = FactoryResponse::findOrFail($id);

        if ($response->factory_id !== auth()->id()) {
            return response()->errors('Unauthorized access to this response.');
        }

        return response()->success($response);
    }
    public function destroy($id)
    {
        dd(auth()->user()->isFactory());
        $response = FactoryResponse::findOrFail($id);

        if ($response->factory_id !== auth()->id()) {
            return response()->errors('Unauthorized access to this response.');
        }

        $response->delete();

        return response()->success('Response deleted successfully');
    }
}
