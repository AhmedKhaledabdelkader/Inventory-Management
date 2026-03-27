<?php

namespace App\Repositories\Contracts;

interface TransferRepositoryInterface
{


    public function upsertFromExternal(array $data);

    public function markMissing(array $existingExternalIds);

    public function resetMissing(array $existingExternalIds);

    public function getAllHoldTransfers(?string $search = null);

    public function updateAction(string $id, string $action);

    public function markPrepared(string $id,string $userId): bool ;

  public function markDropped(string $id, string $reason): bool   ;

  public function markDroppedFromQc(string $id, string $reason): bool;

  public function getDroppedTransfersSummary(): array;

  public function getPreparedTransfers(?string $search = null) ;


  public function getDroppedTransfers(?string $search = null) ;

public function summary(): array;

public function findById(string $id);

public function getVerifiedTransfers(?string $search = null);

public function markAsBoxed(string $id): bool;

public function getVerificationTransfer(string $id);

public function markVerified(string $id, string $method, ?string $notes = null): bool;

public function markRejected(string $id, ?string $notes = null): bool;

public function markBackToHold(string $id, string $reason): bool ;

public function updateVerificationNotes(string $id, ?string $notes = null): bool;
    
}
