<?php

namespace App\PaymentGateways;

use App\Interfaces\PaymentGatewayInterface;
use App\DTO\PaymentGatewayResponse;
use App\DTO\DonationDTO;
use App\Models\DonationDetail;

class DummyPaymentGateway implements PaymentGatewayInterface
{
    public function authorizePayment(): PaymentGatewayResponse
    {
        
    }

    public function processPayment(DonationDTO $donationDTO): DonationDetail
    {
        return new DonationDetail();
    }

    public function refundPayment(): PaymentGatewayResponse
    {

    }
}