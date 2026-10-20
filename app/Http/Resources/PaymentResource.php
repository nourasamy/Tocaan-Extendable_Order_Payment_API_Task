<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'order_id' => $this->order_id,
            'gateway_id' => $this->gateway_id,
            'status' => $this->status->label(),
            'amount' => number_format($this->amount, 2, '.', ''),
           // 'order' => new OrderResource($this->whenLoaded('order')),
            'gateway' => new GatewayResource($this->whenLoaded('gateway')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get the status label
     */
    private function getStatusLabel(): string
    {
        return match($this->status) {
            1 => 'Pending',
            2 => 'Successful',
            3 => 'Failed',
            default => 'Unknown',
        };
    }
}
