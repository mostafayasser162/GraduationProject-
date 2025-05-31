<?php

namespace App\Http\Controllers\Api\Startup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Startup\RatingRequest;
use App\Models\Deal;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(RatingRequest $request, $id)
    {
        $data = $request->validated();
        $startup = auth()->user()->id;
        $deal = Deal::where('id', $id)
            ->wherehas('request', function ($query) use ($startup) {
                $query->where('startup_id', $startup);
            })
            ->where('is_done', 1)
            ->first();

        if (!$deal) {
            return response()->errors('You can only rate completed deals you participated in.');
        }

        // check if already rated
        if (Rating::where('deal_id', $deal->id)
            ->where('startup_id', $startup)->exists()
        ) {
            return response()->errors('You already rated this deal.');
        }

        $rating = Rating::create([
            'startup_id' => $startup,
            'factory_id' => $deal->factory_id,
            'deal_id'    => $deal->id,
            'rate'    => $data['rate'],
            'comment' => $data['comment'] ?? null,
        ]);

        return response()->success('Rating submitted successfully', $rating);
    }
}
