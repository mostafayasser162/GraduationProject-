<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = $request->user();
        $productId = $request->input('product_id');
        $sizeId = $request->input('product_size_id');

        $product = Product::find($productId);
        if (!$product) {
            return response()->errors('Product not found');
        }

        $quantity = $request->input('quantity', 1);

        $user->cart()->syncWithoutDetaching([
            $productId => [
                'quantity' => $quantity,
                'product_size_id' => $sizeId,
            ]
        ]);

        return response()->success('Product added to cart');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->with('subCategory', 'startup', 'images')->get();

        $cart->each(function ($product) {
            $productSizeId = $product->pivot->product_size_id;
            $product->pivot->productSize = \App\Models\Product_size::find($productSizeId);
        });
        $data = CartProductResource::collection($cart);

        $totalPrice = $cart->sum(fn($product) => $product->price * $product->pivot->quantity);
        $totalItems = $cart->sum(fn($product) => $product->pivot->quantity);

        return response()->success([
            'data' =>   $data,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $user = $request->user();
        $productId = $request->input('product_id');
        $productSizeId = $request->input('product_size_id');

        $product = $user->cart()
            ->wherePivot('product_id', $productId)
            ->wherePivot('product_size_id', $productSizeId)
            ->first();


        if (!$product) {
            return response()->errors('Product not found in cart', 404);
        }

        $currentQty = $product->pivot->quantity;

        if ($currentQty > 1) {
            $user->cart()->updateExistingPivot($productId, [
                'quantity' => $currentQty - 1
            ]);
        } else {
            $user->cart()->detach($productId);
        }

        return response()->success('Product quantity updated in cart');
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();
        $user->cart()->detach();

        return response()->errors('Cart cleared');
    }

    //msh mehtagh ahalian 3shan el remove by3ml minus one lel product
    // public function updateQuantity(Request $request)
    // {
    //     $user = $request->user();
    //     $productId = $request->input('product_id');
    //     $quantity = $request->input('quantity');

    //     if ($user->cart()->where('product_id', $productId)->exists()) {
    //         $user->cart()->updateExistingPivot($productId, ['quantity' => $quantity]);
    //         return response()->json(['message' => 'Quantity updated']);
    //     }

    //     return response()->json(['message' => 'Product not found in cart'], 404);
    // }

}
