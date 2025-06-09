<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {
        // dd($this->status);
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'quantity'      => $this->quantity,
            'startup_id'    => $this->startup_id,
            'description'   => $this->description,
            'image'         => $this->image,
            'delivery_date' => $this->delivery_date?->toDateString(),
            'status'        => $this->status,
            'notes'         => $this->notes,

            'startup' => new StartupResource($this->whenLoaded('startup')),

            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}
