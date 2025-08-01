<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    CampaignRepositoryInterface,
    DonationRepositoryInterface,
    DonationDetailRepositoryInterface,
    AdminDashboardRepositoryInterface,
    UserRepositoryInterface,
};
use App\Repositories\{
    CampaignRepository,
    DonationRepository,
    DonationDetailRepository,
    AdminDashboardRepository,
    UserRepository,
};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(DonationRepositoryInterface::class, DonationRepository::class);
        $this->app->bind(DonationDetailRepositoryInterface::class, DonationDetailRepository::class);      
        $this->app->bind(AdminDashboardRepositoryInterface::class, AdminDashboardRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}