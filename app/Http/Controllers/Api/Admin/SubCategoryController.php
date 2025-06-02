<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;
use App\Models\Sub_category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = Sub_category::with('category')->get();
        $subCategories = SubCategoryResource::collection($subCategories);

        return response()->paginate_resource($subCategories);
    }

    public function show($id)
    {
        $subCategory = Sub_category::with('category')->find($id);

        if (!$subCategory) {
            return response()->errors('Sub-category not found');
        }
        $subCategory = new SubCategoryResource($subCategory);

        return response()->success($subCategory);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' =>    'required|file|mimes:jpeg,png,jpg',

        ]);

        // image code
        $file = $data['image'];
        $path = 'storage/' . $file->store('images', 'public');
        $data['image'] = $path;
        // end image code

        $subCategory = Sub_category::create($data);

        return response()->success($subCategory, 'Sub-category created successfully');
    }

    public function update(Request $request, $id)
    {
        $subCategory = Sub_category::find($id);

        if (!$subCategory) {
            return response()->errors('Sub-category not found');
        }

        $data = $request->validate([
            'name' => 'string|max:255',
            'category_id' => 'exists:categories,id',
        ]);

        $subCategory->update($data);

        return response()->success($subCategory, 'Sub-category updated successfully');
    }

    public function destroy($id)
    {
        $subCategory = Sub_category::find($id);

        if (!$subCategory) {
            return response()->errors('Sub-category not found');
        }

        $subCategory->delete();

        return response()->success('Sub-category deleted successfully');
    }
}
