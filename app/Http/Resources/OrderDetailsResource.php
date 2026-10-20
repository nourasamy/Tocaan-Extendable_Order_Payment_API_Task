<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'item_id' => $this->item_id,
            'item_name' => $this->item ? $this->item->name : null,
            'price' => number_format($this->price, 2, '.', ''),
            'qty' => $this->qty,
            'total' => number_format($this->total, 2, '.', '')

        ];
    }
}
