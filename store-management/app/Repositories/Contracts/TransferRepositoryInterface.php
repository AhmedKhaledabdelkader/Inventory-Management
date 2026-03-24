<?php

namespace App\Repositories\Contracts;

interface TransferRepositoryInterface
{


    public function upsertFromExternal(array $data);

    public function markMissing(array $existingExternalIds);

    public function resetMissing(array $existingExternalIds);

    public function getAllHoldTransfers(?string $search = null);

    public function updateAction(string $id, string $action);

    public function markPrepared(string $id): bool ;

  public function markDropped(string $id, string $reason): bool   ;

  public function getDroppedTransfersSummary(): array;

  public function getPreparedTransfers(?string $search = null) ;


  public function getDroppedTransfers(?string $search = null) ;

    public function summary(): array;
    
}
