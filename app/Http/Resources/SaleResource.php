<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'customer' => $this->customer ? new CustomerResource($this->customer) : null,
            'sale_date' => $this->sale_date,
            'total_profit' => $this->total_profit,
            'total_amount' => $this->total_amount,
            'saleItems' => SaleItemResource::collection($this->saleItems),
        ];
    }
}
