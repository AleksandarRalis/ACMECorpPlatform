<?php

namespace App\Repositories;

use App\DTO\DonationDetailDTO;
use App\Models\DonationDetail;
use App\Interfaces\DonationDetailRepositoryInterface;

class DonationDetailRepository implements DonationDetailRepositoryInterface
{
    public function create(DonationDetailDTO $donationDetailDTO): DonationDetail
    {
        return DonationDetail::create($donationDetailDTO->toArray());
    }
}