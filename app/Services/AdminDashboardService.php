<?php

namespace App\Services;

use App\Interfaces\AdminDashboardRepositoryInterface;

class AdminDashboardService
{

    public function __construct(
        protected AdminDashboardRepositoryInterface $adminDashboardRepository
    ) {}

    public function getDashboardData()
    {
        return $this->adminDashboardRepository->getDashboardData();
    }

}