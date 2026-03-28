<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    public function __construct(protected DashboardService $dashboardService) {}


public function getQualityControlsummary()
{
    $summary = $this->dashboardService->getQualityControlDashboardSummary();

    return response()->json([
        'status' => 'success',
        'message' => 'Quality Control Dashboard summary retrieved successfully',
        'result' => $summary,
    ]);
}



    
}
