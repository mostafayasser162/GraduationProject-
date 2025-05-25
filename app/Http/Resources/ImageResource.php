<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'url'        => $this->url,
            'is_main'    => (bool) $this->is_main,
            'product_id' => $this->product_id,

            // لو محتاج ترجع العلاقة مع المنتج:
            // 'product' => new ProductResource($this->whenLoaded('product')),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
