<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'status' => $this->status->label(),
            'subtotal' => number_format($this->subtotal, 2, '.', ''),
            'tax' => number_format($this->tax, 2, '.', ''),
            'tax_type' => $this->tax_type,
            'discount' => number_format($this->discount, 2, '.', ''),
            'discount_type' => $this->discount_type,
            'grand_total' => number_format($this->grand_total, 2, '.', ''),
            'order_details' => OrderDetailsResource::collection($this->whenLoaded('orderDetails')),
            'payments' => $this->whenLoaded('payments') ? PaymentResource::collection($this->payments) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
