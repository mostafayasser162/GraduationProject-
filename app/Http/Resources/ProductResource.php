<?php 


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,  // Product ID
            'name' => $this->name,  // Product name
            'description' => $this->description,  // Product description (nullable)
            'price' => $this->price,  // Product price
            'stock' => $this->stock,  // Product stock
            'startup_id' => $this->startup_id,  // Startup ID (associated with the product)
            'sub_category_id' => $this->sub_category_id,  // Sub-category ID
            'created_at' => $this->created_at,  // Date when the product was created
            'updated_at' => $this->updated_at,  // Date when the product was last updated
        ];
    }
}
