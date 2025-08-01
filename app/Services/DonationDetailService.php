<?php

namespace App\Services;

use App\DTO\DonationDetailDTO;
use App\Models\DonationDetail;
use App\Interfaces\DonationDetailRepositoryInterface;

class DonationDetailService
{
    public function __construct(
        protected DonationDetailRepositoryInterface $donationDetailRepository,
    ) {}

    /**
     * Create a new donation detail
     */
    public function create(DonationDetailDTO $donationDetailDTO): DonationDetail
    {
        return $this->donationDetailRepository->create($donationDetailDTO);
    }
}