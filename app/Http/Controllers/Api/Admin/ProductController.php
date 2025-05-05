<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['startup', 'subCategory.category', 'images'])->paginate();

        // Transform the collection
        $products->getCollection()->transform(function ($product) {
            $product->sub_category = $product->subCategory;
            $product->sub_category->category = $product->sub_category->category;
            unset($product->subCategory);
            return $product;
        });
        return response()->success($products);

    }

    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'images'])->find($id);

        if (!$product) {
            return response()->errors('Product not found');
        }

        $product->sub_category = $product->subCategory;
        $product->sub_category->category = $product->sub_category->category;
        unset($product->subCategory);

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
