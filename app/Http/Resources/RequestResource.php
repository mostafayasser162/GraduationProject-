<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'startup_id'    => $this->startup_id,
            'description'   => $this->description,
            'image'         => $this->image,
            'delivery_date' => $this->delivery_date?->toDateString(),
            'status'        => $this->status,

            'startup' => new StartupResource($this->whenLoaded('startup')),

            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}
