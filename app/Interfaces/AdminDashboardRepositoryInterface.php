<?php

namespace App\Interfaces;

interface AdminDashboardRepositoryInterface
{
    public function getDashboardData(): array;

    public function getOverallStatistics(): array;

    public function getRecentCampaigns(): array;

    public function getRecentDonations(): array;

    public function getMonthlyStats(): array;

    public function getCampaignPerformance(): array;

}