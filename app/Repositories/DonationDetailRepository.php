<?php

namespace App\Repositories;

use App\DTO\DonationDetailDTO;
use App\Models\DonationDetail;
use App\Interfaces\DonationDetailRepositoryInterface;

class DonationDetailRepository implements DonationDetailRepositoryInterface
{
    /**
     * Create a donation detail.
     */
    public function create(DonationDetailDTO $donationDetailDTO): DonationDetail
    {
        return DonationDetail::create($donationDetailDTO->toArray());
    }
}