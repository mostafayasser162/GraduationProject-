<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Enums\StartUps\Status; 


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::whereHas('startup', function ($query) {
            $query->where('status', Status::APPROVED()); // Filter by approved startups
        })
        ->with(['startup', 'subCategory.category', 'images'])
        ->paginate(10);  
        
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
}