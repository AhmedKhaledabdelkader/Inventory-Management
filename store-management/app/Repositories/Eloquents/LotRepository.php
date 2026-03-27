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
        'status' => $runnerId ? 'ready' : 'pending',
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

        'total_runners' => \App\Models\User::whereHas('roles', function ($query) {
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


}
