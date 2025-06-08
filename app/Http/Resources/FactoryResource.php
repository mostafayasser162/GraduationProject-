<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'phone'            => $this->phone,
            'email'            => $this->email,
            'payment_methods'  => $this->payment_methods,
            'payment_account'  => $this->payment_account,
            'status'           => $this->status,
            'description'      => $this->description,
            'created_at'       => $this->created_at?->toDateTimeString(),
            'updated_at'       => $this->updated_at?->toDateTimeString(),
            'deals_count'      => $this->deals_count ?? $this->deals()->count(),
            'deals'            => DealResource::collection($this->whenLoaded('deals')),
            'ratings'          => RatingResource::collection($this->whenLoaded('ratings')),
            'average_rating'   => $this->ratings->avg('rate'),

            'number_of_paid_orders' => $this->number_of_paid_orders,
            'avg_order_value' => round($this->avg_order_value, 2),
            'total_revenue' => $this->total_revenue,
        ];
    }
}
