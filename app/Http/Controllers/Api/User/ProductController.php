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
            ->with(['startup', 'subCategory.category', 'images', 'sizes.size'])
            ->get()
            ->sortByDesc(function ($product) {
                return $product->startup->package_id;
            });

        $products = ProductResource::collection($products);
        return response()->paginate_resource($products);
    }
    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'images', 'sizes.size'])
            ->where('id', $id)
            ->whereHas('startup', function ($q) {
                $q->where('status', Status::APPROVED());
            })
            ->first();

        if (!$product) {
            return response()->errors('Product not found');
        }

        $product = new ProductResource($product);

        return response()->success($product);
    }

    // public function bestSellers()
    // {
    //     $products = Product::bestSellers()
    //         ->with(['images', 'startup', 'subCategory.category']) // include what you need
    //         ->take(45) // top 10 best sellers
    //         ->get();

    //     return response()->success(ProductResource::collection($products));
    // }
    // public function newArrivals()
    // {
    //     $products = Product::newArrivals()
    //         ->with(['images', 'startup', 'subCategory.category']) // include what you need
    //         ->take(45) // top 10 new arrivals
    //         ->get();

    //     return response()->success(ProductResource::collection($products));
    // }

    // public function discountedProducts()
    // {
    //     $products = Product::where(function ($query) {
    //         $query->whereNotNull('discount_percentage')
    //             ->orWhereHas('sizes', function ($q) {
    //                 $q->whereNotNull('discount_percentage');
    //             });
    //     })
    //         ->whereHas('startup', function ($q) {
    //             $q->where('status', Status::APPROVED());
    //         })
    //         ->with(['startup', 'subCategory.category', 'images', 'sizes']) // include sizes
    //         ->get();

    //     if ($products->isEmpty()) {
    //         return response()->errors('No discounted products found.');
    //     }

    //     return response()->success(ProductResource::collection($products));
    // }


    public function bestSellers()
    {
        $search = request('search');

        $products = Product::bestSellers()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with(['images', 'startup', 'subCategory.category'])
            ->take(45)
            ->get()
            // ->sortByDesc(function ($product) {
            //     return $product->startup->package_id;
            // });
            ->sortByDesc(function ($product) {
                return optional($product->startup)->package_id ?? PHP_INT_MIN;
            });


        return response()->success(ProductResource::collection($products));
    }

    public function newArrivals()
    {
        $search = request('search');


        $products = Product::newArrivals()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with(['images', 'startup', 'subCategory.category'])
            ->take(45)
            ->get() 
            ->sortByDesc(function ($product) {
                return optional($product->startup)->package_id ?? PHP_INT_MIN;
            });


        return response()->success(ProductResource::collection($products));
    }
    public function discountedProducts()
    {
        $search = request('search');

        $products = Product::where(function ($query) {
            $query->whereNotNull('discount_percentage')
                ->orWhereHas('sizes', function ($q) {
                    $q->whereNotNull('discount_percentage');
                });
        })
            ->whereHas('startup', function ($q) {
                $q->where('status', Status::APPROVED());
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with(['startup', 'subCategory.category', 'images', 'sizes'])
            ->get()
            ->sortByDesc(function ($product) {
                return $product->startup->package_id;
            });

        if ($products->isEmpty()) {
            return response()->errors('No discounted products found.');
        }

        return response()->success(ProductResource::collection($products));
    }
}
