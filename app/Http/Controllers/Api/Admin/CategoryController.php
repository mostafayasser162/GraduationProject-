<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate();
        $categories = CategoryResource::collection($categories);
        return response()->paginate_resource($categories);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->errors('Category not found');
        }
        $category = new CategoryResource($category);
        return response()->success($category);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = Category::create($data);

        return response()->success($category, 'Category created successfully');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->errors('category not found');
        }
        $category->update($data);

        return response()->success($category, 'Category updated successfully');

    }
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->errors('Category not found');
        }

        $category->delete();
        return response()->success('Category deleted successfully');
    }
}
