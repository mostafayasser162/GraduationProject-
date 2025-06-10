<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    // i want index to return all categories
    public function index()
    {
        $categories = Category::all();
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

}
