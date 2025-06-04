<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $basePrice = $this->price;
        $baseDiscount = $this->discount_percentage;
    
        // If price is null, fallback to minimum product size price
        if (is_null($basePrice) && $this->sizes && $this->sizes->count()) {
            $firstSize = $this->sizes->sortBy('price')->first(); // Pick cheapest or first
            $basePrice = $firstSize->price;
            $baseDiscount = $firstSize->discount_percentage;
        }
    
        // Calculate discounted price
        $discountedPrice = $baseDiscount
            ? round($basePrice - ($basePrice * $baseDiscount / 100), 2)
            : null;
    
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => $basePrice,
            'stock'          => $this->stock,

            'startup'        => new StartupResource($this->whenLoaded('startup')),
            'sub_category'   => new SubCategoryResource($this->whenLoaded('subCategory')),
            'images'         => ImageResource::collection($this->whenLoaded('images')),
            'reviews'        => ReviewResource::collection($this->whenLoaded('reviews')),
            'sizes'          => ProductSizeResource::collection($this->whenLoaded('sizes')),
            
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
            'discount_percentage' => $baseDiscount,
            'discounted_price' => $discountedPrice,
        ];
    }
}
