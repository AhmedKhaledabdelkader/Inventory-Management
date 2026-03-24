<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalTransferResource extends JsonResource
{
    public function toArray($request): array
    {
        $items = $this->resolveItems();

        return [
            'external_id' => $this->valueOf('TransferNbr'),
            'reference_no' => $this->valueOf('TransferNbr'),
            'title' => $this->resolveTitle($items),
            'external_status' => $this->valueOf('Status'),
            'from_warehouse' => $this->valueOf('FromWarehouse'),
            'to_warehouse' => $this->valueOf('ToWarehouse'),
            'qty' => collect($items)->sum('qty'),
            'sku_count' => count($items),
            'items_names' => collect($items)->pluck('name')->filter()->values()->toArray(),
            'items' => $items,
            'payload' => $this->resource,
            'external_updated_at' => $this->dateValueOf('LastModifiedDateTime'),
        ];
    }

    protected function valueOf(string $key): mixed
    {
        $value = data_get($this->resource, $key);

        if (is_array($value)) {
            if (array_key_exists('value', $value)) {
                $innerValue = $value['value'];

                if (is_array($innerValue)) {
                    return null;
                }

                return $innerValue;
            }

            return null;
        }

        return $value;
    }

    protected function dateValueOf(string $key): ?Carbon
    {
        $value = $this->valueOf($key);

        if (blank($value)) {
            return null;
        }

        return Carbon::parse($value);
    }

   protected function resolveItems(): array
{
    $lines = data_get($this->resource, 'TransferLines', []);

    if (!is_array($lines) || empty($lines)) {
        return [];
    }

    return collect($lines)
        ->map(function ($line) {
            $sku = data_get($line, 'InventoryID.value');

            return [
                'sku' => $sku,
                'barcode' => $sku, // added here
                'name' => data_get($line, 'Description.value'),
                'qty' => (int) data_get($line, 'Quantity.value', 0),
                'uom' => data_get($line, 'UOM.value'),
                'line_number' => data_get($line, 'LineNumber.value'),
            ];
        })
        ->filter(fn ($item) => filled($item['sku']) || filled($item['name']))
        ->groupBy(fn ($item) => $item['sku'] ?: $item['name'])
        ->map(function ($group) {
            $first = $group->first();

            return [
                'sku' => $first['sku'],
                'barcode' => $first['sku'], // ensure it's preserved after grouping
                'name' => $first['name'],
                'qty' => $group->sum('qty'),
                'uom' => $first['uom'],
            ];
        })
        ->values()
        ->toArray();
}
    protected function resolveTitle(array $items): ?string
    {
        $title = $this->valueOf('Description');

        if (filled($title)) {
            return trim((string) $title);
        }

        return collect($items)->pluck('name')->filter()->first()
            ?: $this->valueOf('TransferNbr');
    }
}