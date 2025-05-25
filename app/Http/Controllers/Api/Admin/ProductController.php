<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['startup', 'subCategory.category', 'images'])->paginate();

        $products = ProductResource::collection($products);
        return response()->paginate_resource($products);

    }

    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'images'])->find($id);

        if (!$product) {
            return response()->errors('Product not found');
        }
        $product = new ProductResource($product);

        return response()->success($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->errors('Product not found');
        }

        $product->delete();

        return response()->success('Product deleted successfully');
    }
}
