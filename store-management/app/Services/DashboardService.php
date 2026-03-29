<?php

namespace App\Services;

use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService
{
    
    public function __construct(protected DashboardRepositoryInterface $dashboardRepository){} 






 public function getQualityControlDashboardSummary(string $locaionCode): array
    {
        return $this->dashboardRepository->getQualityControlDashboardSummaryByLocation($locaionCode);
    }

     public function getDashboardSummary(): array
    {
        return $this->dashboardRepository->getStoreManagerDashboardSummary();
    }











    
}
