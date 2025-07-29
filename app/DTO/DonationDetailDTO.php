<?php

namespace App\DTO;

class DonationDetailDTO
{
    public function __construct(
        public int $donation_id,
        public string $payment_method,
        public string $transaction_id,
        public string $payment_status,
        public string $gateway_response,
        public string $metadata,
    ) {}
}