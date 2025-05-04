<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['startup', 'subCategory.category', 'images'])->paginate(10);  
             
           // Customize the response
    $products->getCollection()->transform(function ($product) {
        // Merge 'subCategory' data into the product
        $product->sub_category = $product->subCategory;

        // Include category data inside sub_category
        $product->sub_category->category = $product->sub_category->category;

        // Remove the original 'subCategory' relationship (if needed)
        unset($product->subCategory);

        return $product;
    });

        return response()->success($products);
    }

    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'images'])->findOrFail($id);
    
        // Merge 'subCategory' data into the product
        $product->sub_category = $product->subCategory;
    
        // Include category data inside sub_category
        $product->sub_category->category = $product->sub_category->category;
    
        // Remove the original 'subCategory' relationship (if needed)
        unset($product->subCategory);
    
        return response()->success($product);
    }
    

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->success('product deleted successfully');
    }
}
