<?php

namespace App\Repositories\Contracts;

interface LotRepositoryInterface
{
    
public function create(array $data);

public function getAll();

public function findById(string $id) ;

public function assignRunner(string $lotId, ?string $runnerId): bool;

public function getAllWithRunner(?string $search = null);

public function getDashboardStats();

public function getLotsPerRunner();



public function findByCode(string $lotCode);

public function updateStatus(string $lotId, array $data): bool;

public function getRunnerLots(string $runnerId, ?string $status = null);

public function getRunnerDashboardStats(string $runnerId): array;


public function getInTransitLots();

public function getDeliveredLots();

public function markReceived(string $lotId, string $userId, ?string $notes = null): bool;



public function getInTransitLotsByLocation(string $locationCode);

public function getDeliveredLotsByLocation(string $locationCode);

public function getInTransitLotsCountByLocation(string $locationCode): int;

public function getDeliveredLotsCountByLocation(string $locationCode): int;

public function getLotsByLocation(string $locationCode);



}
