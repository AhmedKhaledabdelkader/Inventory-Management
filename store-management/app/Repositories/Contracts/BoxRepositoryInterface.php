<?php

namespace App\Repositories\Contracts;

interface BoxRepositoryInterface
{
    
public function createMany(array $boxesData): array;

public function getAll();


// for the lot popup search boxes
public function getAvailableBoxes(?string $destination = null, ?string $search = null);

public function assignToLot(string $boxId, string $lotId): bool;


public function findByIds(array $ids) ;



public function getBoxesByLocation(string $locationCode);

public function getAvailableBoxesByLocationAndDestination(string $locationCode, string $destination, ?string $search = null);

public function getBoxesCountByLocation(string $locationCode): int;


}
