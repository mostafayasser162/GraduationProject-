<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactoryResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'factory_id'  => $this->factory_id,
            // 'factory_rating' => RatingResource::collection($this->whenLoaded('factory.ratings')),
            //   'factory_ratings' => $this->factory ? RatingResource::collection($this->factory->ratings) : null,

            // 'factory_avg_rating' => $this->factory?->ratings->avg('rate'),

            'request_id'  => $this->request_id,
            'description' => $this->description,
            'price'       => $this->price,
            'image'       => $this->image,
            'status'      => $this->status,

            'factory' => new FactoryResource($this->whenLoaded('factory')),
            'request' => new RequestResource($this->whenLoaded('request')),

            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
