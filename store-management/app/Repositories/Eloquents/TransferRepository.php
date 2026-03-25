<?php

namespace App\Repositories\Eloquents;

use App\Models\Transfer;
use App\Repositories\Contracts\TransferRepositoryInterface;

class TransferRepository implements TransferRepositoryInterface
{
    public Transfer $model ;

    public function __construct(Transfer $transfer) {

        $this->model = $transfer;
    
    }

   public function upsertFromExternal(array $data): Transfer
{
    return $this->model->updateOrCreate(
        [
            'external_id' => (string) ($data['external_id'] ?? ''),
        ],
        [
            'reference_no' => $data['reference_no'] ?? null,
            'title' => $data['title'] ?? null,
            'external_status' => $data['external_status'] ?? null,
            'from_warehouse' => $data['from_warehouse'] ?? null,
            'to_warehouse' => $data['to_warehouse'] ?? null,
            'qty' => (int) ($data['qty'] ?? 0),
            'sku_count' => (int) ($data['sku_count'] ?? 0),
            'items_names' => $data['items_names'] ?? null,
            'items' => $data['items'] ?? null,
            'payload' => $data['payload'] ?? null,
            'external_updated_at' => $data['external_updated_at'] ?? null,
            'last_synced_at' => now(),
            'is_missing_from_api' => false,
        ]
    );
}
    

    public function markMissing(array $existingExternalIds): int
    {
        return $this->model->whereNotIn('external_id', $existingExternalIds)
            ->update(['is_missing_from_api' => true]);
    }

    public function resetMissing(array $existingExternalIds): int
    {
        return $this->model->whereIn('external_id', $existingExternalIds)
            ->update(['is_missing_from_api' => false]);
    }

public function getAllHoldTransfers(?string $search = null)
{
    $query = $this->model
        ->where('external_status', 'On Hold')
        ->whereNull('current_action');

    $this->applySearch($query, $search);

    return $query->latest()->get();
}

public function getPreparedTransfers(?string $search = null)
{
    $query = $this->model
        ->where('current_action', 'prepared');

    $this->applySearch($query, $search);

    return $query->orderByDesc('prepared_at')->get();
}


public function getDroppedTransfers(?string $search = null)
{
    $query = $this->model
        ->where('current_action', 'dropped');

    $this->applySearch($query, $search);

    return $query->orderByDesc('dropped_at')->get();
}

protected function applySearch($query, ?string $search): void
{
    if (!$search) {
        return;
    }

    $query->where(function ($q) use ($search) {
        $q->where('external_id', 'like', "%{$search}%")
          ->orWhere('title', 'like', "%{$search}%")
          ->orWhere('items_names', 'like', "%{$search}%");
    });
}

    public function updateAction(string $id, string $action): bool
    {
        return $this->model->findOrFail($id)->update([
            'current_action' => $action,
        ]);
    }

    public function markPrepared(string $id,string $userId): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'current_action' => 'prepared',
        'drop_reason' => null,
        'prepared_at' => now(), // clear reason if previously dropped
        'prepared_by' => $userId,
    ]);
}


public function markDropped(string $id, string $reason): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'current_action' => 'dropped',
        'drop_reason' => $reason,
        'dropped_at' => now()
    ]);
}

public function markDroppedFromQc(string $id, string $reason): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'current_action' => 'dropped',
        'verification_status' => 'rejected',
        'drop_reason' => $reason,
        'dropped_at' => now(),
    ]);
}

    public function summary(): array
    {
        return [
            'total' => $this->model->count(),
            'prepared' => $this->model->where('current_action', 'prepared')->count(),
            'dropped' => $this->model->where('current_action', 'dropped')->count(),
            'pending' => $this->model->whereNull('current_action')->count(),
            'missing' => $this->model->where('is_missing_from_api', true)->count(),
        ];
    }


   
public function getDroppedTransfersSummary(): array
{
    $droppedTransfers = $this->model
        ->where('current_action', 'dropped')
        ->latest()
        ->get();

    return [
        'dropped_transfers_count' => $droppedTransfers->count(),
        'all_transfers_count' => $this->model->count(),
        'dropped_transfers' => $droppedTransfers,
    ];
}



public function findById(string $id)
{
    return $this->model->find($id);
}

public function getVerificationTransfer(string $id)
{
    return $this->model->find($id);
}

public function markVerified(string $id, string $method, ?string $notes = null): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'verification_status' => 'verified',
        'verified_at' => now(),
        'verification_method' => $method,
        'verification_notes' => $notes,
    ]);
}

public function markRejected(string $id, ?string $notes = null): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'verification_status' => 'rejected',
        'verification_notes' => $notes,
    ]);
}

public function updateVerificationNotes(string $id, ?string $notes = null): bool
{
    $transfer = $this->model->findOrFail($id);

    return $transfer->update([
        'verification_notes' => $notes,
    ]);
}


















}
