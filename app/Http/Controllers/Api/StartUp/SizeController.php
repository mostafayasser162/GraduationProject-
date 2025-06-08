<?php

namespace App\Http\Controllers\Api\StartUp;


use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{

    public function index()
    {
        $startupId = auth()->user()->id;
        $sizes = Size::where('startup_id', $startupId)->get();
        return response()->success('Sizes retrieved successfully.', ['sizes' => $sizes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sizes' => 'required|array',
            'sizes.*' => 'required|string|max:255'
        ]);

        $startup_id = auth()->user()->id;

        $sizes = [];
        foreach ($request->sizes as $sizeValue) {
            $sizes[] = Size::create([
                'startup_id' => $startup_id,
                'size'       => $sizeValue,
            ]);
        }

        return response()->success('Sizes created successfully.', ['sizes' => $sizes]);
    }

    public function destroy($id)
    {
        $size = Size::find($id);
        if (!$size) {
            return response()->errors('Size not found.', 404);
        }

        if ($size->startup_id !== auth()->user()->id) {
            return response()->errors('You are not authorized to delete this size.', 403);
        }

        $size->delete();

        return response()->success('Size deleted successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255'
        ]);

        $size = Size::find($id);

        if (!$size || $size->startup_id !== auth()->user()->id) {
            return response()->errors('Size not found.', 403);
        }

        $size->update($request->only('size'));

        return response()->success('Size updated successfully.', ['size' => $size]);
    }
}
