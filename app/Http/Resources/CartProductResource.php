<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id'     => $this->id,
            'name'           => $this->name,
            'price'          => $this->price,
            'quantity'       => $this->pivot->quantity,
            'total'          => $this->price * $this->pivot->quantity,
            'sub_category'   => optional($this->subCategory)->name,
            'startup_id'     => $this->startup_id,
            'startup'        => new StartupResource($this->startup),
            'images'         => $this->images->pluck('url'),
            'product_size' => new ProductSizeResource($this->pivot->productSize),
        ];
    }
}
