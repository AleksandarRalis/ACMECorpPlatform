<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    CampaignRepositoryInterface,
    DonationRepositoryInterface,
    DonationDetailRepositoryInterface
};
use App\Repositories\{
    CampaignRepository,
    DonationRepository,
    DonationDetailRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(DonationRepositoryInterface::class, DonationRepository::class);
        $this->app->bind(DonationDetailRepositoryInterface::class, DonationDetailRepository::class);    
    }
}