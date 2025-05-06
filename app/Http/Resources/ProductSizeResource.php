<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'color' => $this->color ? $this->color->name : null,
            'size' => $this->size ? $this->size->name : null, 
            'price' => $this->price,
            'stock' => $this->stock,
        ];
    }
}
