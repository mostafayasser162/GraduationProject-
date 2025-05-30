<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'deal_date' => $this->deal_date,
            'is_done' => (bool) $this->is_done,

            // Relationships
            'request' => new RequestResource($this->whenLoaded('request')),
            'factory' => new FactoryResource($this->whenLoaded('factory')),
            'factory_response' => new FactoryResponseResource($this->whenLoaded('factoryResponse')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
