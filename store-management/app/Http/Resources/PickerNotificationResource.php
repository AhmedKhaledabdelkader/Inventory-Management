<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PickerNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => data_get($this->data, 'type'),
            'message' => data_get($this->data, 'message'),
            'transfer_id' => data_get($this->data, 'transfer_id'),
            'reference_no' => data_get($this->data, 'reference_no'),
            'issue_type' => data_get($this->data, 'issue_type'),
            'description' => data_get($this->data, 'description'),
            'reported_at' => data_get($this->data, 'reported_at'),
            'is_read' => !is_null($this->read_at),
            'read_at' => $this->read_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}