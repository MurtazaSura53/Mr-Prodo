<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'unit' => $this->unit,
            'qty' => $this->qty,
            'price' => $this->price,
            'profit' => $this->profit,
            'subtotal' => $this->subtotal,
            'product' => new ProductResource($this->product),
        ];
    }
}
