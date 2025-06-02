<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'total_price' => $this->total_price,
            'status'      => $this->status,
            'address_id'  => $this->address_id,
            'second_phone' => $this->second_phone,

            'user'         => new UserResource($this->whenLoaded('user')),
            'order_items'  => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'address'      => new AddressResource($this->whenLoaded('address')),

            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
