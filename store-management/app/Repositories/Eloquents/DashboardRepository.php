<?php

namespace App\Repositories\Eloquents;

use App\Models\Box;
use App\Models\Lot;
use App\Models\Transfer;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{

    public function getQualityControlDashboardSummary(): array
{
    return [
        'total_boxes' => Box::count(),
        'verified_transfers' => Transfer::where('verification_status', 'verified')->count(),
        'pending' => Lot::where('status', 'pending')->count(),
        'in_transit' =>Lot::where('status', 'in_transit')->count(),
        'delivered' => Lot::where('status', 'delivered')->count(),
    ];
}
  
}
