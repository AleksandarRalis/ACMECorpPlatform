<?php

namespace App\Services;

use App\DTO\DonationDTO;
use App\Models\Donation;
use App\Interfaces\DonationRepositoryInterface;

class DonationService
{
    public function __construct(
        protected DonationRepositoryInterface $donationRepository
    ) {}

    public function create(DonationDTO $donationDTO): Donation
    {
        return $this->donationRepository->create($donationDTO);
    }
}