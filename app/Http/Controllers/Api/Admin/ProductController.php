<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {

        $products = Product::with(['startup', 'images', 'sizes'])->get();

        return response()->success($products);
    }

    public function show($id)
    {
        $product = Product::with(['startup', 'images', 'sizes'])->findOrFail($id);

        return response()->success($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->success('product deleted successfully');
    }
}
