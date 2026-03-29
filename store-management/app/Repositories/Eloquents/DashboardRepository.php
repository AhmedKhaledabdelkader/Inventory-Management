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

public function getQualityControlDashboardSummaryByLocation(string $locationCode): array
{
    return [
        'total_boxes' => Box::whereHas('transfer', function ($q) use ($locationCode) {
            $q->where('to_warehouse', $locationCode);
        })->count(),

        'verified_transfers' => Transfer::where('verification_status', 'verified')
            ->where('to_warehouse', $locationCode)
            ->count(),

        'pending' => Lot::where('status', 'pending')
            ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
                $q->where('to_warehouse', $locationCode);
            })
            ->count(),

        'in_transit' => Lot::where('status', 'in_transit')
            ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
                $q->where('to_warehouse', $locationCode);
            })
            ->count(),

        'delivered' => Lot::where('status', 'delivered')
            ->whereHas('boxes.transfer', function ($q) use ($locationCode) {
                $q->where('to_warehouse', $locationCode);
            })
            ->count(),
    ];
}


public function getStoreManagerDashboardSummary(): array
{
    return [

        'total_boxes' => Box::count(),
        'onhold_transfers' => Transfer::where('external_status', 'On Hold')->whereNull('current_action')->count(),
        'in_transit' => Lot::where('status', 'in_transit')->count(),
        'delivered' => Lot::where('status', 'delivered')->count(),
    ];
}





  
}
