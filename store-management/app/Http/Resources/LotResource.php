<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{

     public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'lot_code' => $this->lot_code,
            'destination' => $this->destination,
            'total_boxes' => (int) $this->total_boxes,
            'total_items' => (int) $this->total_items,
            'status' => $this->status,
            'boxes' => $this->boxes->map(function ($box) {
                return [
                    'id' => $box->id,
                    'box_code' => $box->box_code,
                    'destination' => $box->destination,
                    'status' => $box->status,
                    'transfer_no' => $box->transfer?->reference_no,
                ];
            }),

            'runner' => [
              'id' => $this->runner?->id,
              'name' => $this->runner?->name,
              ],
             'created_by' => $this->user?->name,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
    
}
