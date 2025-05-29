<?php

namespace App\Http\Controllers\Api\StartUp;


use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function index($startupId)
    {
        $sizes = Size::where('startup_id', $startupId)->get();
        return response()->success('Sizes retrieved successfully.', ['sizes' => $sizes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'startup_id' => 'required|exists:startups,id',
            'size' => 'required|string|max:255'
        ]);

        $size = Size::create($request->only('startup_id', 'size'));

        return response()->success('Size created successfully.', ['size' => $size]);
    }

    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        return response()->success('Size deleted successfully.');

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255'
        ]);

        $size = Size::findOrFail($id);
        $size->update($request->only('size'));

        return response()->success('Size updated successfully.', ['size' => $size]);
    }
}
