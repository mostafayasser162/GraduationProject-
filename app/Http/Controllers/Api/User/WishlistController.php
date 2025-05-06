<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WishlistResource; // Import WishlistResource

class WishlistController extends Controller
{
    // Add product to wishlist
    public function addToWishlist(Request $request, $productId)
    {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->errors('Product not found', 404);
        }

        // Check if product is already in the wishlist
        $existingWishlistItem = Wishlist::where('user_id', $user->id)
                                        ->where('product_id', $productId)
                                        ->first();
        if ($existingWishlistItem) {
            return response()->success('Product already in wishlist');
        }

        // Add product to the wishlist
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->success(new WishlistResource($wishlist));
    }

    // Get user's wishlist
    public function getWishlist()
    {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // Get user's wishlist
        $wishlist = Wishlist::with('product')->where('user_id', $user->id)->get();

        return response()->success(WishlistResource::collection($wishlist));
    }

    // Remove product from wishlist
    public function removeFromWishlist($productId)
    {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->errors('User not authenticated', 401);
        }

        // Find the wishlist item
        $wishlistItem = Wishlist::where('user_id', $user->id)
                                ->where('product_id', $productId)
                                ->first();

        if (!$wishlistItem) {
            return response()->errors('Product not found in wishlist', 404);
        }

        // Delete the wishlist item
        $wishlistItem->delete();

        return response()->success('Product removed from wishlist');
    }
}