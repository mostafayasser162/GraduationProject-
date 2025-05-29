<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_colors;
use App\Models\Product_size; // Ensure the correct class name matches the file name
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;


class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => $request->has_sizes ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'sub_category_id'  => 'required|exists:sub_categories,id',
            'has_sizes'        => 'required|boolean',
            'colors'           => 'array',
            'colors.*.name'    => 'required_with:colors|string',
            'colors.*.code'    => 'nullable|string',
            'sizes'            => 'array',
            'sizes.*.color_index' => 'required|integer', // index in colors array
            'sizes.*.size_id'  => 'required|exists:sizes,id',
            'sizes.*.price'    => 'required|numeric|min:0',
            'sizes.*.stock'    => 'required|integer|min:0',

            'images'           => 'required|array|min:1',
            'images.*.file'    => 'required|file|image|max:2048',
            'images.*.is_main' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Create the product
            $product = Product::create([
                'startup_id'     => Auth::id(),
                'name'           => $request->name,
                'description'    => $request->description,
                'price'          => $request->has_sizes ? null : $request->price,
                'stock'          => $request->stock,
                'sub_category_id'=> $request->sub_category_id,
            ]);

            if ($request->has_sizes) {
                // Add colors
                $colorMap = [];
                foreach ($request->colors as $index => $color) {
                    $colorModel = Product_colors::create([
                        'product_id' => $product->id,
                        'color_name' => $color['name'],
                        // 'color_code' => $color['code'],
                    ]);
                    $colorMap[$index] = $colorModel->id;
                }

                // Add sizes
                foreach ($request->sizes as $size) {
                    Product_size::create([
                        'product_id' => $product->id,
                        'color_id'   => $colorMap[$size['color_index']],
                        'size_id'    => $size['size_id'],
                        'price'      => $size['price'],
                        'stock'      => $size['stock'],
                    ]);
                }
            }
            foreach ($request->images as $imageData) {
                $imagePath = 'storage/' . $imageData['file']->store('images', 'public');
    
                \App\Models\Image::create([
                    'product_id' => $product->id,
                    'url'        => $imagePath,
                    'is_main'    => $imageData['is_main'],
                ]);
            }

            
            DB::commit();
            return response()->success(('Product added successfully.'),
            new ProductResource($product->load('startup', 'subCategory.category', 'sizes.color', 'sizes.size', 'images'))
        );
            
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->errors($e->getMessage(), 500); // Temporary for debugging
    }
    
    }

    public function index()
    {
        $products = Product::with(['startup', 'subCategory.category', 'sizes', 'sizes.size', 'sizes.color'])
            ->where('startup_id', Auth::id())
            ->paginate();


        return response()->paginate_resource(ProductResource::collection($products));
        

    }
    public function show($id)
    {
        $product = Product::with(['startup', 'subCategory.category', 'sizes', 'sizes.size', 'sizes.color','images','reviews.user'])
            ->where('id', $id)
            ->where('startup_id', Auth::id())
            ->first();

        if (!$product) {

            return response()->errors('Product not found', 404);
            
        }


        $product = new ProductResource($product);

        return response()->success($product);
    }
    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('startup_id', Auth::id())->first();

        if (!$product) {
            return response()->errors('Product not found', 404);

        }

        $product->delete();

        return response()->success('Product deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => $request->has_sizes ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'sub_category_id'  => 'required|exists:sub_categories,id',
            'has_sizes'        => 'required|boolean',
            'colors'           => 'array',
            'colors.*.name'    => 'required_with:colors|string',
            'colors.*.code'    => 'nullable|string',
            'sizes'            => 'array',
            'sizes.*.color_index' => 'required|integer', // index in colors array
            'sizes.*.size_id'  => 'required|exists:sizes,id',
            'sizes.*.price'    => 'required|numeric|min:0',
            'sizes.*.stock'    => 'required|integer|min:0',
    
            'images'           => 'required|array|min:1',
            'images.*.file'    => 'required|file|image|max:2048',
            'images.*.is_main' => 'required|boolean',
        ]);
    
        DB::beginTransaction();
    
        try {
            $product = Product::where('id', $id)->where('startup_id', Auth::id())->first();
    
            if (!$product) {
                return response()->errors('Product not found', 404);
            }
    
            // Update product details
            $product->update([
                'name'           => $request->name,
                'description'    => $request->description,
                'price'          => $request->has_sizes ? null : $request->price,
                'stock'          => $request->stock,
                'sub_category_id'=> $request->sub_category_id,
            ]);
    
            // Handle colors and sizes
            if ($request->has_sizes) {
                // Clear existing sizes and colors
                Product_colors::where('product_id', $product->id)->delete();
                Product_size::where('product_id', $product->id)->delete();
    
                // Add colors
                $colorMap = [];
                foreach ($request->colors as $index => $color) {
                    $colorModel = Product_colors::create([
                        'product_id' => $product->id,
                        'color_name' => $color['name'],
                        // 'color_code' => $color['code'],
                    ]);
                    $colorMap[$index] = $colorModel->id;
                }
    
                // Add sizes
                foreach ($request->sizes as $size) {
                    Product_size::create([
                        'product_id' => $product->id,
                        'color_id'   => $colorMap[$size['color_index']],
                        'size_id'    => $size['size_id'],
                        'price'      => $size['price'],
                        'stock'      => $size['stock'],
                    ]);
                }
            } else {
                // If not has_sizes, clear all sizes and colors
                Product_colors::where('product_id', $product->id)->delete();
                Product_size::where('product_id', $product->id)->delete();
            }
    
            // Handle images
            \App\Models\Image::where('product_id', $product->id)->delete();
            foreach ($request->images as $imageData) {
                $imagePath = 'storage/' . $imageData['file']->store('images', 'public');
    
                \App\Models\Image::create([
                    'product_id' => $product->id,
                    'url'        => $imagePath,
                    'is_main'    => $imageData['is_main'],
                ]);
            }
    
            DB::commit();
    
            return response()->success('Product updated successfully.',
                new ProductResource($product->load('startup', 'subCategory.category', 'sizes.color', 'sizes.size', 'images'))
            );
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->errors('Error updating product', 500);
        }
    }
    

}