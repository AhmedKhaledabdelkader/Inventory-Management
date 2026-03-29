<?php

namespace App\Services;

use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService
{
    
    public function __construct(protected DashboardRepositoryInterface $dashboardRepository){} 






 public function getQualityControlDashboardSummary(): array
    {
        return $this->dashboardRepository->getQualityControlDashboardSummary();
    }

     public function getDashboardSummary(): array
    {
        return $this->dashboardRepository->getStoreManagerDashboardSummary();
    }











    
}
