<?php 

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')), // Load product details
            'product_size' => new ProductSizeResource($this->whenLoaded('productSize')), // Load product size if exists
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
