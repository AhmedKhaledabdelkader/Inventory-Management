<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    public function __construct(protected DashboardService $dashboardService) {}


public function getQualityControlsummary(Request $request)
{

    $locationCode = $request->user()->location_code;

    $summary = $this->dashboardService->getQualityControlDashboardSummary($locationCode);

    return response()->json([
        'status' => 'success',
        'message' => 'Quality Control Dashboard summary retrieved successfully',
        'result' => $summary,
    ]);
}

public function getStoreManagerDashboard()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Store manager dashboard retrieved successfully',
            'result' => $this->dashboardService->getDashboardSummary(),
        ]);
    }



    
}
