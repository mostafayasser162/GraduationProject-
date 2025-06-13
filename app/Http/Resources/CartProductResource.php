<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StartupResource;
use App\Http\Resources\ProductSizeResource;
use App\Http\Resources\ProductResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {


    //     return [
    //         'product_id'     => $this->id,
    //         'name'           => $this->name,
    //         'price'          => $this->price,
    //         'quantity'       => $this->pivot->quantity,
    //         'total'          => $this->price * $this->pivot->quantity,
    //         'sub_category'   => optional($this->subCategory)->name,
    //         'startup_id'     => $this->startup_id,
    //         'startup'        => new StartupResource($this->startup),
    //         'images'         => $this->images->pluck('url'),
    //         'product_size'   => new ProductSizeResource($this->pivot->productSize),
    //         'size'           => $this->pivot->productSize->size ?? 'N/A',
    //         'color'          => $this->pivot->productSize->color ?? 'N/A',
    //         'stock'          => $this->pivot->productSize->stock ?? 0,  // Add stock from product_size
    //         'price'          => $this->pivot->productSize->price ?? 0,  // Add price from product_size
    //         // 'size'           => $this->pivot->productSize->size ? $this->pivot->productSize->size : 'N/A', // Ensuring proper size handling
    //         // 'color'          => $this->pivot->productSize->color ? $this->pivot->productSize->color->color_name : 'N/A', // Ensuring proper color handling
    //     ];
    // }



    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->product_size_id
                ? optional($this->pivot->productSize)->price
                : $this->price,
            'stock' => $this->pivot->product_size_id
                ? optional($this->pivot->productSize)->stock
                : $this->stock,
            'product_size' => $this->pivot->product_size_id
                ? new ProductSizeResource($this->pivot->productSize)
                : null,
            'startup' => new StartupResource($this->startup),
            // 'image' => $this->mainImage
            //     ? asset('storage/' . $this->mainImage->url)
            //     : null,
            'image' => str_starts_with($this->mainImage->url, 'storage/')
                ? $this->mainImage->url
                : 'storage/' . $this->mainImage->url,


            'sub_category' => $this->whenLoaded('subCategory', fn () => new SubCategoryResource($this->subCategory)),

            'product' => new ProductResource($this->whenLoaded('product')), // Load product details
            'discount_percentage' => $this->discount_percentage ?? 0, // Assuming discount_percentage is a field in the Product model
            // 'dicounted_price' => $this->pivot->product_size_id
            //     ? optional($this->pivot->productSize)->discountedPrice()
            //     : $this->discountedPrice(),
            
            // Alternatively, return all image URLs:
            // 'images' => $this->images->pluck('url')->map(fn($url) => asset('storage/' . $url)),
        ];
    }
}
