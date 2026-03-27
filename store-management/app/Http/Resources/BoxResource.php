<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'box_code' => $this->box_code,
            'destination' => $this->destination,
            'box_number' => (int) $this->box_number,
            'total_boxes' => (int) $this->total_boxes,
            'status' => $this->status,
            'transfer' => [
                'id' => $this->transfer?->id,
                'reference_no' => $this->transfer?->reference_no,
                'title' => $this->transfer?->title,
                'qty' => (int) ($this->transfer?->qty ?? 0),
                 'items' => collect($this->transfer?->items ?? [])->pluck('name')->toArray(),
              
            ],
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}