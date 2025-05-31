<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'rate'       => $this->rate,
            'comment'    => $this->comment,
            'startup'    => [
                'id'    => $this->startup->id,
                'name'  => $this->startup->name,
            ],
            'factory'    => [
                'id'    => $this->factory->id,
                'name'  => $this->factory->name,
            ],
            'deal_id'    => $this->deal_id,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
