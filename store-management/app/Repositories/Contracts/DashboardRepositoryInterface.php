<?php

namespace App\Repositories\Contracts;

interface DashboardRepositoryInterface
{
    


public function getQualityControlDashboardSummary(): array;

public function getStoreManagerDashboardSummary(): array;


}
