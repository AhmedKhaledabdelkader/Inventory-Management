<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'reference_no' => $this->reference_no,
            'title' => $this->resolveTitle(),
            //'items_names' => $this->resolveItemsNames(),
            'items' => $this->resolveItems(),
            'external_status' => $this->external_status,
            'from_warehouse' => $this->from_warehouse,
            'to_warehouse' => $this->to_warehouse,
            'qty' => (int) $this->qty,
            'sku_count' => (int) $this->sku_count,
            'current_action' => $this->current_action,
            'drop_reason' => $this->drop_reason,
            'dropped_at' => $this->dropped_at?->toISOString(),
            'is_missing_from_api' => (bool) $this->is_missing_from_api,
        ];
    }

    protected function resolveTitle(): ?string
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        return data_get($this->payload, 'Description.value');
    }
protected function resolveItemsNames(): array
{
    if (!empty($this->items_names)) {

        echo "Ahmed11111"  ;
        // if stored as string, convert to array
        return is_array($this->items_names)
            ? $this->items_names
            : array_map('trim', explode(',', $this->items_names));
    }


    

    $lines = data_get($this->payload, 'TransferLines', []);

    if (!is_array($lines)) {
        return [];
    }

    return collect($lines)
        ->pluck('Description.value')
        ->filter()
        ->unique()
        ->values()
        ->toArray();
}

protected function resolveItems(): array
{
    if (is_array($this->items) && !empty($this->items)) {
        return $this->items;
    }

    return [];
}
}