<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Enums\StartUps\Status;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::whereHas('startup', function ($q) {
            $q->where('status', Status::APPROVED());
        });
        // dd($query);

        if (request()->has('sub_category_id')) {
            $query->where('sub_category_id', request('sub_category_id'));
        }

        if (request()->has('category_id')) {
            $query->whereHas('subCategory.category', function ($q) {
                $q->where('id', request('category_id'));
            });
        }

        // 4. Filter by price range
        if (request()->has('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request()->has('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }

        $products = $query
            ->with(['startup', 'subCategory.category', 'images'])
            ->paginate();
            // dd($products);

        $products = ProductResource::collection($products);
        return response()->paginate_resource($products);
    }

    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'images'])
            ->where('id', $id)
            ->whereHas('startup', function ($q) {
                $q->where('status', Status::APPROVED());
            })
            ->firstOrFail(); // will return 404 if not found

        $product = new ProductResource($product);

        return response()->success($product);
    }


}
