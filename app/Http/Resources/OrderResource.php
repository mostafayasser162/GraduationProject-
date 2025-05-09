<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'total' => $this->total_price,
            'created_at' => $this->created_at,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')) // Load order items

        ];
    }
}
