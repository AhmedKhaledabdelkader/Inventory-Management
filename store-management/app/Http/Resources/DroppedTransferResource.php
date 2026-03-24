<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DroppedTransferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'reference_no' => $this->reference_no,
            'external_status' => $this->external_status,
            'from_warehouse' => $this->from_warehouse,
            'to_warehouse' => $this->to_warehouse,
           // 'qty' => $this->qty,
           // 'sku_count' => $this->sku_count,
            'current_action' => $this->current_action,
            'drop_reason' => $this->drop_reason,
            'dropped_at' => $this->dropped_at?->format('Y-m-d H:i'),
        
        ];
    }
}