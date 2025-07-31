<?php

namespace App\DTO;

use App\Services\PaymentResult;

class DonationDetailDTO
{
    public function __construct(
        public string $payment_method,
        public string $transaction_id,
        public string $payment_status,
        public string $gateway_response,
        public string $metadata,
        public string $processed_at,
        public ?string $failed_at,
        public ?string $failed_reason,
        public int $donation_id,
        public int $donor_id
    ) {}

    public static function fromPaymentResult(PaymentResult $paymentResult, int $donationId, int $donorId): DonationDetailDTO
    {
        return new self(
            payment_method: $paymentResult->payment_method,
            transaction_id: $paymentResult->transaction_id,
            payment_status: $paymentResult->payment_status,
            gateway_response: $paymentResult->gateway_response,
            metadata: $paymentResult->metadata,
            processed_at: $paymentResult->processed_at,
            failed_at: $paymentResult->failed_at,
            failed_reason: $paymentResult->failed_reason,
            donation_id: $donationId,
            donor_id: $donorId,
        );
    }

    public function toArray(): array
    {
        return [
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'payment_status' => $this->payment_status,
            'gateway_response' => $this->gateway_response,
            'metadata' => $this->metadata,
            'processed_at' => $this->processed_at,
            'failed_at' => $this->failed_at,
            'failed_reason' => $this->failed_reason,
            'donation_id' => $this->donation_id,
            'donor_id' => $this->donor_id,
        ];
    }
}