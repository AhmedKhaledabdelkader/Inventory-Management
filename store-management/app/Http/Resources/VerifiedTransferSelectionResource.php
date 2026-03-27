<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifiedTransferSelectionResource extends JsonResource
{
     public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'title' => $this->title,

            // ✅ Only item names
            'items' => collect($this->items)->pluck('name')->toArray(),

            'qty' => $this->qty,
          //  'sku_count' => $this->sku_count,
            'verification_status' => $this->verification_status,
        ];
    }


    
protected function resolveItems(): array
{
    if (is_array($this->items) && !empty($this->items)) {
        return $this->items;
    }

    return [];
}
}