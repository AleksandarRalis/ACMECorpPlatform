<?php

namespace App\Services;

use App\DTO\DonationDTO;
use App\Models\Donation;
use App\DTO\DonationDetailDTO;
use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway,
        protected DonationService $donationService,
        protected DonationDetailService $donationDetailService,
    ) {}

    /**
     * Process the payment.
     */
    public function processPayment(DonationDTO $donationDTO): PaymentResult
    {
        return $this->paymentGateway->processPayment($donationDTO);
    }

    /**
     * Process the donation.
     */
    public function processDonation(DonationDTO $donationDTO, PaymentResult $paymentResult, int $donorId): Donation
    {
        return DB::transaction(function () use ($donationDTO, $paymentResult, $donorId){
            $donation = $this->donationService->create($donationDTO);
            $donationDetailDTO = DonationDetailDTO::fromPaymentResult($paymentResult, $donation->id, $donorId);
            $this->donationDetailService->create($donationDetailDTO);
            $campaign = $donation->campaign;
            $campaign->current_amount += $donation->amount;
            $campaign->save();
            
            return $donation;
        });
    }
}
