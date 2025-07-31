<?php

namespace App\Repositories;

use App\DTO\DonationDTO;
use App\Models\Donation;
use App\Interfaces\DonationRepositoryInterface;

class DonationRepository implements DonationRepositoryInterface
{
    public function create(DonationDTO $donationDTO): Donation
    {
        return Donation::create($donationDTO->toArray());
    }
}