<?php

namespace App\Repositories\Eloquents;

use App\Models\Box;
use App\Repositories\Contracts\BoxRepositoryInterface;

class BoxRepository implements BoxRepositoryInterface
{

    public Box $model ;

    public function __construct(Box $box) {

        $this->model = $box;
    
    }


     public function createMany(array $boxesData): array
    {
        $created = [];

        foreach ($boxesData as $boxData) {
            $created[] = $this->model->create($boxData);
        }

        return $created;
    }

    public function getAll()
    {
        return $this->model->with('transfer')->latest()->get();
    }


    public function findByIds(array $ids)
{
    return $this->model->with('transfer')->whereIn('id', $ids)->get();
}

    public function getAvailableBoxes(?string $destination = null, ?string $search = null)
{
    $query = $this->model
        ->with('transfer')
        ->whereNull('lot_id')
        ->where('status', 'pending');

    if ($destination) {
        $query->where('destination', $destination);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('box_code', 'like', "%{$search}%")
              ->orWhereHas('transfer', function ($t) use ($search) {
                  $t->where('reference_no', 'like', "%{$search}%");
              });
        });
    }

    return $query->get();
}

public function assignToLot(string $boxId, string $lotId): bool
{
    $box = $this->model->findOrFail($boxId);

    return $box->update([
        'lot_id' => $lotId,
        'status' => 'ready',
    ]);
}


/*..............location id.....................*/

public function getBoxesByLocation(string $locationCode)
{
    return $this->model
        ->with('transfer')
        ->whereHas('transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->latest()
        ->get();
}

public function getAvailableBoxesByLocationAndDestination(string $locationCode, string $destination, ?string $search = null)
{
    $query = $this->model
        ->with('transfer')
        ->whereNull('lot_id')
        ->where('status', 'pending')
        ->where('destination', $destination)
        ->whereHas('transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        });

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('box_code', 'like', "%{$search}%")
              ->orWhereHas('transfer', function ($t) use ($search) {
                  $t->where('reference_no', 'like', "%{$search}%");
              });
        });
    }

    return $query->get();
}

public function getBoxesCountByLocation(string $locationCode): int
{
    return $this->model
        ->whereHas('transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->count();
}





    
}
