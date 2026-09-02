<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
        ];
    }
}
