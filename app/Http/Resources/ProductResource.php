<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => $this->price,
            'stock'        => $this->stock,
            // 'startup_id'     => $this->startup_id,
            // 'sub_category_id'=> $this->sub_category_id,

            'startup'        => new StartupResource($this->whenLoaded('startup')),
            'sub_category'   => new SubCategoryResource($this->whenLoaded('subCategory')),
            'images'         => ImageResource::collection($this->whenLoaded('images')),

            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
