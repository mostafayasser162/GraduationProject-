<?php

namespace App\Http\Controllers\Api\General;

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

    public function startupSubCategories()
    {
        $startup = auth()->user();
        if (!$startup || !$startup->isStartup()) {
            return response()->errors('Unauthorized', 401);
        }

        $subCategories = Sub_category::with('category')
            ->where('category_id', $startup->categories_id)
            ->get();

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
}
