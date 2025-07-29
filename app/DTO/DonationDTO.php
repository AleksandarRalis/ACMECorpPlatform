<?php

namespace App\DTO;

class DonationDTO
{
    public function __construct(
        public int $campaign_id,
        public int $donor_id,
        public float $amount,
        public string $message,
    ) {}
}