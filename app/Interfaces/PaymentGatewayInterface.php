<?php   

namespace App\Interfaces;
use App\Models\DonationDetail;
use App\DTO\DonationDTO;
use App\DTO\DonationDetailDTO;

interface PaymentGatewayInterface
{
    public function authorizePayment(); // To be implemented

    public function processPayment(DonationDTO $donationDTO): DonationDetail; // Currently using dummy payment gateway

    public function refundPayment(); // To be implemented
    
}