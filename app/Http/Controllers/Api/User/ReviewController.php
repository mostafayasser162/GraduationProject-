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

        $review = Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comments'   => $request->comments,
        ]);

        return response()->success(new ReviewResource($review));
    }

    public function productReviews($productId)
    {
        $reviews = Review::with('user')->where('product_id', $productId)->latest()->paginate(10);

        return response()->paginate_resource(ReviewResource::collection($reviews));
    }
}

