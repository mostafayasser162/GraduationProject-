<?php

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comments'   => 'nullable|string',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if the authenticated user has ordered this product
        $hasOrderedProduct = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $userId)
            ->where('order_items.product_id', $productId)
            ->exists();

        if (!$hasOrderedProduct) {
            return response()->errors("You can only review products you have ordered.", 403);
        }

        // Optionally check if the user already reviewed the product
        $alreadyReviewed = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($alreadyReviewed) {
            return response()->errors("You have already reviewed this product.", 409);
        }

        $review = Review::create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'rating'     => $request->rating,
            'comments'   => $request->comments,
        ]);
        $review->load('product');

        return response()->success(new ReviewResource($review));
    }


    public function productReviews($productId)
    {
        $reviews = Review::with('user' , 'product')->where('product_id', $productId)->latest()->get();

        return response()->paginate_resource(ReviewResource::collection($reviews));
    }
}
