<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'supplier_name' => $this->supplier_name,
            'purchase_date' => $this->purchase_date,
            'total_amount' => $this->total_amount,
            'purchase_items' => PurchaseItemResource::collection($this->purchaseItems),
        ];
    }
}
