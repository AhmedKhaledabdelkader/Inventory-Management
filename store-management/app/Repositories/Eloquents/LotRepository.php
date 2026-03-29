<?php

namespace App\Repositories\Eloquents;

use App\Models\Lot;
use App\Models\User;
use App\Repositories\Contracts\LotRepositoryInterface;

class LotRepository implements LotRepositoryInterface
{
     public Lot $model ;

    public function __construct(Lot $lot) {

        $this->model = $lot;
    
    }


public function create(array $data): Lot
{
    return $this->model->create($data);
}

public function getAll()
{
    return $this->model->with(['user','boxes.transfer'])->latest()->get();
}


public function findById(string $id)
{
    return $this->model->find($id);
}


public function assignRunner(string $lotId, ?string $runnerId): bool
{
    $lot = $this->model->findOrFail($lotId);
    
    return $lot->update([
        'runner_id' => $runnerId,
        'assigned_at' => $runnerId ? now() : null,
        'status' => $runnerId ? 'ready_to_go' : 'pending',
    ]);
}

public function getAllWithRunner(?string $search = null)
{
    $query = $this->model
        ->with(['runner', 'boxes'])
        ->latest();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('lot_code', 'like', "%{$search}%")
              ->orWhere('destination', 'like', "%{$search}%")
              ->orWhereHas('runner', function ($r) use ($search) {
                  $r->where('name', 'like', "%{$search}%");
              });
        });
    }

    return $query->get();
}



    public function getDashboardStats()
{
    return [
        'total_lots' => $this->model->count(),
        'unassigned' => $this->model->whereNull('runner_id')->count(),
        'assigned' => $this->model->whereNotNull('runner_id')->count(),
        'in_transit' => $this->model->where('status', 'in_transit')->count(),

        'total_runners' => User::whereHas('roles', function ($query) {
            $query->where('name', 'Runner');
        })->count(),
    ];
}


public function getLotsPerRunner()
{
    return User::whereHas('roles', function ($q) {
        $q->where('name', 'Runner');
    })
    ->withCount('lots')
    ->get();
}



/*.................Runner..............*/



public function findByCode(string $lotCode): ?Lot
{
    return $this->model->with(['boxes', 'runner'])
        ->where('lot_code', $lotCode)
        ->first();
}

public function updateStatus(string $lotId, array $data): bool
{
    $lot = $this->model->findOrFail($lotId);

    return $lot->update($data);
}

public function getRunnerLots(string $runnerId, ?string $status = null)
{
    $query = $this->model
        ->with(['boxes'])
        ->where('runner_id', $runnerId)
        ->latest();

    if ($status) {
        $query->where('status', $status);
    }

    return $query->get();
}

public function getRunnerDashboardStats(string $runnerId): array
{
    return [
        'ready_to_go' => $this->model
            ->where('runner_id', $runnerId)
            ->where('status', 'ready_to_go')
            ->count(),

        'in_transit' => $this->model
            ->where('runner_id', $runnerId)
            ->where('status', 'in_transit')
            ->count(),

        'delivered' => $this->model
            ->where('runner_id', $runnerId)
            ->where('status', 'delivered')
            ->count(),
    ];
}


public function getInTransitLots()
{
    return $this->model
        ->with(['runner', 'boxes'])
        ->where('status', 'in_transit')
        ->latest()
        ->get();
}

public function getDeliveredLots()
{
    return $this->model
        ->with(['runner', 'boxes'])
        ->where('status', 'delivered')
        ->latest()
        ->get();
}


public function markReceived(string $lotId, string $userId, ?string $notes = null): bool
{
    $lot = $this->model->findOrFail($lotId);

    return $lot->update([
        'status' => 'received',
        'received_at' => now(),
        'received_by' => $userId,
        'receipt_notes' => $notes,
    ]);
}



/*.............location id .............*/ 


public function getInTransitLotsByLocation(string $locationCode)
{
    return $this->model
        ->with(['runner', 'boxes.transfer'])
        ->where('status', 'in_transit')
        ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->latest()
        ->get();
}

public function getDeliveredLotsByLocation(string $locationCode)
{
    return $this->model
        ->with(['runner', 'boxes.transfer'])
        ->where('status', 'delivered')
        ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->latest()
        ->get();
}

public function getInTransitLotsCountByLocation(string $locationCode): int
{
    return $this->model
        ->where('status', 'in_transit')
        ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->count();
}

public function getDeliveredLotsCountByLocation(string $locationCode): int
{
    return $this->model
        ->where('status', 'delivered')
        ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })
        ->count();
}

public function getLotsByLocation( string $locationCode) {
    $query = $this->model
        ->with(['user','runner', 'boxes.transfer'])
        ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
            $q->where('from_warehouse', $locationCode);
        })
        ->latest();


    return $query->get();
}




}
