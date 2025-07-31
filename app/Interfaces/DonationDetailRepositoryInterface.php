<?php

namespace App\Interfaces;

use App\DTO\DonationDetailDTO;
use App\Models\DonationDetail;

interface DonationDetailRepositoryInterface
{
    public function create(DonationDetailDTO $donationDetailDTO): DonationDetail;
}