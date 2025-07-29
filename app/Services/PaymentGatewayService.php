<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use App\DTO\DonationDTO;
use App\Models\DonationDetail;
use App\Interfaces\DonationDetailRepositoryInterface;

class PaymentGatewayService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway,
        protected DonationDetailRepositoryInterface $donationDetailRepository
    ) {}

    public function authorizePayment()
    {

    }

    public function processPayment(DonationDTO $donationDTO): DonationDetail
    {
        return $this->paymentGateway->processPayment($donationDTO);
    }

    public function refundPayment()
    {

    }
}