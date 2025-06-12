<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
// use App\Http\Resources\ProductSizeResource;
// use App\Http\Resources\ProductColorResource;
// use App\Http\Resources\ColorProductResource; // Commented out as it may not exist
use Illuminate\Http\Resources\Json\JsonResource;


class ProductSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    // ProductSizeResource.php
        // public function toArray($request)
        // {
        //     return [
        //         'id' => $this->id,
        //         // 'size' => $this->size ? $this->size->size : 'N/A', // Ensure size data is returned
        //         // 'color' => $this->color ? $this->color->color_name : 'N/A', // Ensure color data is returned
        //         'size' => $this->size && is_object($this->size) ? new ProductSizeResource($this->size) : null,
        //         'color' => $this->color && is_object($this->color) ? new ProductColorResource($this->color) : null,
        //         'price' => $this->price,
        //         'stock' => $this->stock,
        //     ];
        // }


        // public function toArray($request)
        // {
        //     return [
        //         'id' => $this->id,
        //         'size_id' => $this->size_id,
        //         'size' => optional($this->size)->name, // assuming relation `size`
        //         'color' => $this->color ? $this->color->color_name : 'N/A', // Fallback to color_name if resource is missing
        //         'price' => $this->price,
        //         'stock' => $this->stock,
        //     ];
        // }

        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'price' => $this->price,
                'stock' => $this->stock,
                'size' => $this->size ? [
                    'id' => $this->size->id,
                    'name' => $this->size->size,
                ] : null,
                'discount_percentage' => $this->discount_percentage,
                'discounted_price' => $this->discount_percentage
                        ? round($this->price - ($this->price * $this->discount_percentage / 100), 2)
                        : null,
            ];
        }

}
