<?php

namespace App\Interfaces;

use App\DTO\DonationDTO;
use App\Models\Donation;

interface DonationRepositoryInterface
{
    public function create(DonationDTO $donationDTO): Donation;
}