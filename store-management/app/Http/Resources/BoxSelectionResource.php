<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxSelectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'box_code' => $this->box_code,
            'destination' => $this->destination,
            'status' => $this->status,
            'transfer' => [
                'id' => $this->transfer?->id,
                'reference_no' => $this->transfer?->reference_no,
                'items' => collect($this->transfer?->items ?? [])->pluck('name')->toArray(),
                'qty' => (int) ($this->transfer?->qty ?? 0),
            ],
        ];
    }
}
