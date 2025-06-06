<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $phone
 * @property mixed $name
 * @property mixed $email
 * @property object $role
 * @property mixed $blocked_at
 * @property mixed $deleted_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        /** @var self|User $this */
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'order_items' => OrderItemResource::collection($this->whenLoaded('orders.items')),
            'address' => AddressResource::collection($this->whenLoaded('addresses')),

        ];
    }
}
