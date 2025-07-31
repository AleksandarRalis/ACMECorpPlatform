<?php   

namespace App\Interfaces;
use App\DTO\DonationDTO;
use App\Services\PaymentResult;

interface PaymentGatewayInterface
{
    public function authorizePayment(); // To be implemented

    public function processPayment(DonationDTO $donationDTO): PaymentResult; // Currently using dummy payment gateway

    public function refundPayment(); // To be implemented
    
}