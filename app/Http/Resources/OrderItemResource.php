<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->whenLoaded('product', $this->product?->name),
            'quantity' => $this->quantity,
            'unit_price' => (string) $this->unit_price,
            'subtotal' => (string) $this->subtotal,
        ];
    }
}
