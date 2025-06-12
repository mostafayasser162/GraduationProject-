<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Product;
use App\Models\Product_size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    // public function addToCart(Request $request)
    // {
    //     $user = $request->user();
    //     $productId = $request->input('product_id');

    //     $sizeId = $request->input('product_size_id');

    //     $product = Product::find($productId);
    //     if (!$product) {
    //         return response()->errors('Product not found');
    //     }

    //     $quantity = $request->input('quantity', 1);

    //     $user->cart()->syncWithoutDetaching([
    //         $productId => [
    //             'quantity' => $quantity,
    //             'product_size_id' => $sizeId,
    //         ]
    //     ]);

    //     return response()->success('Product added to cart');
    // }
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productSize = Product_size::findOrFail($request->product_size_id);

        // Verify the product size belongs to the requested product
        if ($productSize->product_id !== $request->product_id) {
            return response()->errors('Invalid product size for this product', 400);
        }

        // Check if product is already in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'product_size_id' => $request->product_size_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->success('Product added to cart successfully');
    }

    public function index(Request $request)
    {
        $user = $request->user();


        $cart = $user->cart()
            ->with(['subCategory', 'startup', 'mainImage'])
            ->get();

        // Don't forget to eager load productSize relations if used:
        $cart->each(function ($product) {
            if ($product->pivot->product_size_id) {
                $product->pivot->productSize = \App\Models\Product_size::with(['size', 'color'])->find($product->pivot->product_size_id);
            }
        });
        $data = CartProductResource::collection($cart);

        $totalPrice = $cart->sum(function ($product) {
            $price = $product->pivot->product_size_id
                ? $product->pivot->productSize->price
                : $product->price;
            return $price * $product->pivot->quantity;
        });

        $totalItems = $cart->sum(fn($product) => $product->pivot->quantity);

        return response()->success([
            'data' => $data,
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

    public function addToCartQuantity(Request $request)
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

        $user->cart()->updateExistingPivot($productId, [
            'quantity' => $currentQty + 1,
            'product_size_id' => $productSizeId
        ]);

        // Fetch the updated product with the newest quantity
        $updatedProduct = $user->cart()
            ->wherePivot('product_id', $productId)
            ->wherePivot('product_size_id', $productSizeId)
            ->first();

        return response()->success([
            'message' => 'Product quantity increased in cart',
            'product' => $updatedProduct
        ]);
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();
        $user->cart()->detach();

        return response()->success('Cart cleared');
    }
    // i wantt function to delete one product from the cart

    public function removeProductFromCart(Request $request , $id)
    {
        $user = $request->user();
        $productId = $id;
        $productSizeId = $request->input('product_size_id'); // nullable
    
        // Check if the product exists in the cart
        $productInCart = \DB::table('cart_product')
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->when($productSizeId !== null, function ($query) use ($productSizeId) {
                return $query->where('product_size_id', $productSizeId);
            }, function ($query) {
                return $query->whereNull('product_size_id');
            })
            ->first();
    
        if (!$productInCart) {
            return response()->errors('Product not found in cart', 404);
        }
    
        // Delete the entry from cart_products
        $deleted = \DB::table('cart_product')
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->when($productSizeId !== null, function ($query) use ($productSizeId) {
                return $query->where('product_size_id', $productSizeId);
            }, function ($query) {
                return $query->whereNull('product_size_id');
            })
            ->delete();
    
        if ($deleted) {
            return response()->success('Product deleted from the cart successfully');
        } else {
            return response()->errors('Failed to delete product from cart', 500);
        }
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
